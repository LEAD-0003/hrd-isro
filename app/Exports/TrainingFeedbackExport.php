<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TrainingFeedbackExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize
{
    protected Collection $feedbacks;

    public function __construct(Collection $feedbacks)
    {
        $this->feedbacks = $feedbacks;
    }

    public function collection(): Collection
    {
        return $this->feedbacks;
    }

    public function headings(): array
    {
        return [
            'S.No.',
            'Employee Name',
            'Employee ID',
            'Centre',
            'Training Program',
            'Training Mode',
            'Status',
            'Overall Rating',
            // Content
            'Clarity of Objectives',
            'Relevance to Role',
            'Quality of Materials',
            'Practical Applicability',
            // Facilitator
            'Subject Knowledge',
            'Clarity of Communication',
            'Engagement & Interaction',
            'Time Management',
            // Logistics
            'Venue / Platform Quality',
            'Organization & Coordination',
            'Accommodation & Boarding',
            'Transportation',
            // Overall
            'Met Expectations',
            // Text feedback
            'Most Useful Aspect',
            'Areas for Improvement',
            // Meta
            'Feedback Date',
        ];
    }

    protected static int $rowIndex = 1;

    public function map($feedback): array
    {
        static $index = 0;
        $index++;

        $app      = $feedback->trainingApplication;
        $user     = $app?->user;
        $training = $app?->training;
        $centre   = $app?->centreRel;

        return [
            $index,
            $user?->name                    ?? 'N/A',
            $user?->employee_code           ?? 'N/A',
            $centre?->name                  ?? 'N/A',
            $training?->title               ?? 'N/A',
            $training?->mode                ?? 'N/A',
            ucfirst($app?->status ?? 'pending'),
            $feedback->overall_rating,
            // Content
            $feedback->clarity_objectives,
            $feedback->relevance_to_role,
            $feedback->quality_materials,
            $feedback->practical_applicability,
            // Facilitator
            $feedback->subject_knowledge,
            $feedback->clarity_communication,
            $feedback->engagement_interaction,
            $feedback->time_management,
            // Logistics
            $feedback->venue_platform_quality,
            $feedback->organization_coordination,
            $feedback->accommodation_boarding,
            $feedback->transportation,
            // Overall
            $feedback->met_expectations,
            // Text
            $feedback->most_useful_aspect,
            $feedback->areas_for_improvement,
            // Meta
            $feedback->created_at?->format('d-M-Y'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row: dark background, white bold text
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1e293b'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                    'wrapText'   => true,
                ],
            ],
        ];
    }
}