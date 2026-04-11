<?php

namespace App\Filament\Hq\Pages;

use Filament\Pages\Page;
use App\Models\Centre;
use App\Models\Training;
use App\Models\TrainingFeedback;
use App\Models\TrainingApplication;
use App\Exports\TrainingFeedbackExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class FeedbackReport extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-presentation-chart-bar';
    protected static string  $view            = 'filament.admin.pages.feedback-report';
    protected static ?string $navigationLabel = 'Feedback Analytics';
    protected static ?string $title           = 'Training Feedback Dashboard';
    protected static ?int $navigationSort = 11;

    public ?string $filterYear        = null;
    public ?string $filterCentreId    = null;
    public ?string $filterDesignation = null;
    public ?string $filterTrainingId  = null;
    public ?string $filterMode        = null;

    public ?int   $activeCentreId  = null;
    public bool   $compareMode     = false;
    public ?string $compareCentreA  = null;
    public ?string $compareCentreB  = null;
    public bool   $showModal       = false;
    public ?int   $modalFeedbackId = null;
    public string $activeTab       = 'infograph';

    public function mount(): void
    {
        $this->filterYear = (string) now()->year;
    }

    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'filter')) {
            $this->activeCentreId = null;
            $this->compareMode = false;
        }
    }

    private function buildBaseQuery(?int $forceCentreId = null)
    {
        $centreId = $forceCentreId ?? (!empty($this->filterCentreId) ? (int) $this->filterCentreId : null);

        return TrainingFeedback::query()
            ->join('training_applications', 'training_feedbacks.training_application_id', '=', 'training_applications.id')
            ->join('trainings', 'training_applications.training_id', '=', 'trainings.id')
            ->when(!empty($this->filterYear), fn($q) => $q->whereYear('training_feedbacks.created_at', $this->filterYear))
            ->when($centreId !== null, fn($q) => $q->where('training_applications.centre', $centreId))
            ->when(!empty($this->filterDesignation), fn($q) => $q->where('training_applications.nominee_designation', $this->filterDesignation))
            ->when(!empty($this->filterTrainingId), fn($q) => $q->where('training_applications.training_id', $this->filterTrainingId))
            ->when(!empty($this->filterMode), fn($q) => $q->where('trainings.mode', $this->filterMode));
    }

    private function buildCentreAggregate(?int $forceCentreId = null)
    {
        return TrainingFeedback::query()
            ->join('training_applications', 'training_feedbacks.training_application_id', '=', 'training_applications.id')
            ->join('centres', 'training_applications.centre', '=', 'centres.id')
            ->join('trainings', 'training_applications.training_id', '=', 'trainings.id')
            ->select(
                'centres.id   as centre_id',
                'centres.name as centre_name',
                DB::raw('COUNT(training_feedbacks.id)                   as total_feedbacks'),
                DB::raw('ROUND(AVG(overall_rating),2)                   as avg_overall'),
                DB::raw('ROUND(AVG(clarity_objectives),2)               as avg_clarity'),
                DB::raw('ROUND(AVG(relevance_to_role),2)                as avg_relevance'),
                DB::raw('ROUND(AVG(quality_materials),2)                as avg_quality'),
                DB::raw('ROUND(AVG(subject_knowledge),2)                as avg_knowledge'),
                DB::raw('ROUND(AVG(venue_platform_quality),2)           as avg_venue'),
                DB::raw('ROUND(AVG(engagement_interaction),2)           as avg_engagement'),
                DB::raw('ROUND(AVG(practical_applicability),2)          as avg_practical'),
                DB::raw('ROUND(AVG(met_expectations),2)                 as avg_met'),
                DB::raw("SUM(CASE WHEN training_applications.status = 'completed' THEN 1 ELSE 0 END) as completed_count")
            )
            ->when(!empty($this->filterYear), fn($q) => $q->whereYear('training_feedbacks.created_at', $this->filterYear))
            ->when(!empty($this->filterDesignation), fn($q) => $q->where('training_applications.nominee_designation', $this->filterDesignation))
            ->when(!empty($this->filterTrainingId), fn($q) => $q->where('training_applications.training_id', $this->filterTrainingId))
            ->when(!empty($this->filterMode), fn($q) => $q->where('trainings.mode', $this->filterMode))
            ->when(
                $forceCentreId !== null,
                fn($q) => $q->where('centres.id', $forceCentreId),
                fn($q) => $q->when(
                    !empty($this->filterCentreId),
                    fn($q2) => $q2->where('centres.id', (int) $this->filterCentreId)
                )
            )
            ->groupBy('centres.id', 'centres.name')
            ->orderByDesc('avg_overall')
            ->get();
    }

    #[Computed] 
    public function centreOptions(): array
    {
        return Centre::orderBy('name')->pluck('name', 'id')->toArray();
    }

    #[Computed]
    public function trainingOptions(): array
    {
        return Training::orderBy('title')->pluck('title', 'id')->toArray();
    }

    #[Computed]
    public function designationOptions(): array
    {
        return TrainingApplication::whereNotNull('nominee_designation')
            ->distinct()
            ->orderBy('nominee_designation')
            ->pluck('nominee_designation', 'nominee_designation')
            ->toArray();
    }

    #[Computed]
    public function allCentresList()
    {
        return Centre::orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function allFeedbacks()
    {
        return $this->buildBaseQuery()
            ->with(['trainingApplication.user', 'trainingApplication.training', 'trainingApplication.centreRel'])
            ->select('training_feedbacks.*')
            ->get();
    }

    #[Computed]
    public function allCentres()
    {
        return $this->buildCentreAggregate();
    }

    #[Computed]
    public function totalFeedbacks(): int
    {
        return $this->allCentres->sum('total_feedbacks');
    }

    #[Computed]
    public function globalAvg(): float
    {
        return round((float) ($this->allCentres->avg('avg_overall') ?? 0), 2);
    }

    #[Computed]
    public function topCentre()
    {
        return $this->allCentres->first();
    }

    #[Computed]
    public function globalMetrics(): array
    {
        $fb = $this->allFeedbacks;
        return [
            'Clarity'       => round((float) ($fb->avg('clarity_objectives')        ?? 0), 2),
            'Relevance'     => round((float) ($fb->avg('relevance_to_role')         ?? 0), 2),
            'Quality'       => round((float) ($fb->avg('quality_materials')         ?? 0), 2),
            'Practical'     => round((float) ($fb->avg('practical_applicability')   ?? 0), 2),
            'Knowledge'     => round((float) ($fb->avg('subject_knowledge')         ?? 0), 2),
            'Communication' => round((float) ($fb->avg('clarity_communication')     ?? 0), 2),
            'Engagement'    => round((float) ($fb->avg('engagement_interaction')    ?? 0), 2),
            'Time Mgmt'     => round((float) ($fb->avg('time_management')           ?? 0), 2),
            'Venue'         => round((float) ($fb->avg('venue_platform_quality')    ?? 0), 2),
            'Organization'  => round((float) ($fb->avg('organization_coordination') ?? 0), 2),
            'Overall'       => round((float) ($fb->avg('overall_rating')            ?? 0), 2),
        ];
    }

    #[Computed]
    public function ratingDist(): array
    {
        $fb = $this->allFeedbacks;
        return [
            1 => $fb->where('overall_rating', 1)->count(),
            2 => $fb->where('overall_rating', 2)->count(),
            3 => $fb->where('overall_rating', 3)->count(),
            4 => $fb->where('overall_rating', 4)->count(),
            5 => $fb->where('overall_rating', 5)->count(),
        ];
    }

    #[Computed]
    public function activeCentreData()
    {
        if (! $this->activeCentreId) return null;
        return $this->buildCentreAggregate($this->activeCentreId)->first();
    }

    #[Computed]
    public function activeCentreTrainings()
    {
        if (! $this->activeCentreId) return collect();
        return $this->buildBaseQuery($this->activeCentreId)
            ->select(
                'trainings.id    as training_id',
                'trainings.title as training_title',
                'trainings.mode  as training_mode',
                DB::raw('COUNT(training_feedbacks.id)            as total_feedbacks'),
                DB::raw('ROUND(AVG(overall_rating),2)            as avg_overall'),
                DB::raw('ROUND(AVG(clarity_objectives),2)        as avg_clarity'),
                DB::raw('ROUND(AVG(relevance_to_role),2)         as avg_relevance'),
                DB::raw('ROUND(AVG(quality_materials),2)         as avg_quality'),
                DB::raw('ROUND(AVG(subject_knowledge),2)         as avg_knowledge'),
                DB::raw('ROUND(AVG(venue_platform_quality),2)    as avg_venue'),
                DB::raw('ROUND(AVG(engagement_interaction),2)    as avg_engagement'),
                DB::raw("SUM(CASE WHEN training_applications.status='completed' THEN 1 ELSE 0 END) as completed_count")
            )
            ->groupBy('trainings.id', 'trainings.title', 'trainings.mode')
            ->orderByDesc('avg_overall')
            ->get();
    }

    #[Computed]
    public function activeCentreFeedbacks()
    {
        if (! $this->activeCentreId) return collect();
        return $this->buildBaseQuery($this->activeCentreId)
            ->with(['trainingApplication.user', 'trainingApplication.training'])
            ->select('training_feedbacks.*')
            ->get();
    }

    #[Computed]
    public function compareData(): array
    {
        if (! $this->compareMode || ! $this->compareCentreA || ! $this->compareCentreB) {
            return [];
        }
        return [
            'a' => $this->buildCentreAggregate((int) $this->compareCentreA)->first(),
            'b' => $this->buildCentreAggregate((int) $this->compareCentreB)->first(),
        ];
    }

    #[Computed]
    public function modalFeedback()
    {
        if (! $this->showModal || ! $this->modalFeedbackId) return null;
        return TrainingFeedback::with(['trainingApplication.user', 'trainingApplication.training', 'trainingApplication.centreRel'])
            ->find($this->modalFeedbackId);
    }

    public function drillInto(int $centreId): void
    {
        $this->activeCentreId = $centreId;
        $this->compareMode    = false;
        unset($this->activeCentreData, $this->activeCentreTrainings, $this->activeCentreFeedbacks);
    }

    public function backToOverview(): void
    {
        $this->activeCentreId = null;
        $this->compareMode    = false;
    }

    public function startCompare(): void
    {
        $this->compareMode    = true;
        $this->activeCentreId = null;
    }

    public function cancelCompare(): void
    {
        $this->compareMode    = false;
        $this->compareCentreA = null;
        $this->compareCentreB = null;
    }

    public function viewFeedback(int $id): void
    {
        $this->modalFeedbackId = $id;
        $this->showModal       = true;
        unset($this->modalFeedback);
    }

    public function closeModal(): void
    {
        $this->showModal       = false;
        $this->modalFeedbackId = null;
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function downloadExcel(): mixed
    {
        return Excel::download(new TrainingFeedbackExport($this->allFeedbacks), 'feedback_' . ($this->filterYear ?: now()->year) . '_' . now()->format('Ymd') . '.xlsx');
    }

    public function downloadCentreExcel(): mixed
    {
        if (! $this->activeCentreId) return null;
        return Excel::download(new TrainingFeedbackExport($this->activeCentreFeedbacks), 'feedback_centre_' . $this->activeCentreId . '.xlsx');
    }

    public function downloadSingleExcel(int $id): mixed
    {
        $fb = TrainingFeedback::with(['trainingApplication.user', 'trainingApplication.training', 'trainingApplication.centreRel'])->findOrFail($id);
        return Excel::download(new TrainingFeedbackExport(collect([$fb])), 'feedback_' . $id . '.xlsx');
    }
}