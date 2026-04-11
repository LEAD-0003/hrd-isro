<?php

namespace App\Filament\Admin\Resources\FaqResource\Pages;

use App\Filament\Admin\Resources\FaqResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\Faq;

class CreateFaq extends CreateRecord
{
    protected static string $resource = FaqResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $faqEntries = $data['faq_entries'] ?? [];

        $attachment = $data['attachment'] ?? null;

        $firstRecord = null;

        foreach ($faqEntries as $entry) {
            $record = Faq::create([
                'question'   => $entry['question'],
                'answer'     => $entry['answer'],
                'sort_order' => $entry['sort_order'] ?? 0,
                'is_active'  => $entry['is_active'] ?? true,
                'attachment' => $attachment,
            ]);

            if (!$firstRecord) {
                $firstRecord = $record;
            }
        }

        return $firstRecord ?? new Faq();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}