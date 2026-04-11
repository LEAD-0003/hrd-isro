<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingFeedback;
use App\Models\Centre;
use App\Models\Training;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * ── SETUP ─────────────────────────────────────────────────────────────────
 *  composer require barryvdh/laravel-dompdf
 **/
class FeedbackPdfController extends Controller
{
    public function download(Request $request)
    {
        $year        = $request->input('year')          ?: now()->year;
        $centreId    = $request->input('centre_id')     ?: null;
        $trainingId  = $request->input('training_id')   ?: null;
        $mode        = $request->input('training_type') ?: null;
        $designation = $request->input('designation')   ?: null;

        // Base query closure
        $base = fn() => TrainingFeedback::query()
            ->join('training_applications', 'training_feedbacks.training_application_id', '=', 'training_applications.id')
            ->join('trainings', 'training_applications.training_id', '=', 'trainings.id')
            ->when($year,        fn($q) => $q->whereYear('training_feedbacks.created_at', $year))
            ->when($centreId,    fn($q) => $q->where('training_applications.centre', $centreId))
            ->when($trainingId,  fn($q) => $q->where('training_applications.training_id', $trainingId))
            ->when($mode,        fn($q) => $q->where('trainings.mode', $mode))
            ->when($designation, fn($q) => $q->where('training_applications.nominee_designation', $designation));

        // Metric averages
        $metricFields = [
            'Clarity'       => 'clarity_objectives',
            'Relevance'     => 'relevance_to_role',
            'Quality'       => 'quality_materials',
            'Practical'     => 'practical_applicability',
            'Knowledge'     => 'subject_knowledge',
            'Communication' => 'clarity_communication',
            'Engagement'    => 'engagement_interaction',
            'Time Mgmt'     => 'time_management',
            'Venue'         => 'venue_platform_quality',
            'Organization'  => 'organization_coordination',
            'Overall'       => 'overall_rating',
        ];
        $metrics = [];
        foreach ($metricFields as $label => $field) {
            $metrics[$label] = round((float) ($base()->avg("training_feedbacks.{$field}") ?? 0), 2);
        }

        $totalFeedbacks = $base()->count('training_feedbacks.id');
        $globalAvg      = round((float) ($base()->avg('training_feedbacks.overall_rating') ?? 0), 2);

        // Rating distribution
        $ratingDist = [];
        foreach ([1, 2, 3, 4, 5] as $r) {
            $ratingDist[$r] = $base()->where('training_feedbacks.overall_rating', $r)->count();
        }

        // Centre breakdown
        $centreData = TrainingFeedback::query()
            ->join('training_applications', 'training_feedbacks.training_application_id', '=', 'training_applications.id')
            ->join('centres',   'training_applications.centre', '=', 'centres.id')
            ->join('trainings', 'training_applications.training_id', '=', 'trainings.id')
            ->select(
                'centres.name as centre_name',
                DB::raw('COUNT(training_feedbacks.id)          as total_feedbacks'),
                DB::raw('ROUND(AVG(overall_rating),2)          as avg_overall'),
                DB::raw('ROUND(AVG(clarity_objectives),2)      as avg_clarity'),
                DB::raw('ROUND(AVG(relevance_to_role),2)       as avg_relevance'),
                DB::raw('ROUND(AVG(quality_materials),2)       as avg_quality'),
                DB::raw('ROUND(AVG(subject_knowledge),2)       as avg_knowledge'),
                DB::raw('ROUND(AVG(venue_platform_quality),2)  as avg_venue'),
                DB::raw("SUM(CASE WHEN training_applications.status='completed' THEN 1 ELSE 0 END) as completed_count")
            )
            ->when($year,        fn($q) => $q->whereYear('training_feedbacks.created_at', $year))
            ->when($trainingId,  fn($q) => $q->where('training_applications.training_id', $trainingId))
            ->when($mode,        fn($q) => $q->where('trainings.mode', $mode))
            ->when($designation, fn($q) => $q->where('training_applications.nominee_designation', $designation))
            ->when($centreId,    fn($q) => $q->where('centres.id', $centreId))
            ->groupBy('centres.id', 'centres.name')
            ->orderByDesc('avg_overall')
            ->get();

        // Top trainings
        $topTrainings = TrainingFeedback::query()
            ->join('training_applications', 'training_feedbacks.training_application_id', '=', 'training_applications.id')
            ->join('trainings', 'training_applications.training_id', '=', 'trainings.id')
            ->select('trainings.title', DB::raw('ROUND(AVG(overall_rating),2) as avg_rating'), DB::raw('COUNT(training_feedbacks.id) as feedback_count'))
            ->when($year,     fn($q) => $q->whereYear('training_feedbacks.created_at', $year))
            ->when($centreId, fn($q) => $q->where('training_applications.centre', $centreId))
            ->groupBy('trainings.id', 'trainings.title')
            ->orderByDesc('avg_rating')
            ->limit(10)
            ->get();

        // Human-readable filter labels
        $filterLabels = [
            'Year'        => $year,
            'Centre'      => $centreId    ? (Centre::find($centreId)?->name    ?? $centreId)    : 'All',
            'Training'    => $trainingId  ? (Training::find($trainingId)?->title ?? $trainingId) : 'All',
            'Mode'        => $mode        ? ucfirst(str_replace('_', ' ', $mode))                : 'All',
            'Designation' => $designation ?? 'All',
        ];

        $pdf = Pdf::loadView('filament.admin.pages.feedback-pdf', compact(
            'metrics',
            'totalFeedbacks',
            'globalAvg',
            'ratingDist',
            'centreData',
            'topTrainings',
            'filterLabels',
            'year'
        ))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'          => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'dpi'                  => 150,
            ]);

        return $pdf->download('feedback_report_' . $year . '_' . now()->format('Ymd') . '.pdf');
    }
}