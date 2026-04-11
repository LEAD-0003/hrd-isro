<?php

namespace App\Filament\Coordinator\Pages;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class MyProfile extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'My Profile';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.coordinator.pages.my-profile';

    public ?array $data = [];
    public bool $isEditing = false;

    public function mount(): void
    {
        $this->form->fill(auth()->user()->attributesToArray());
    }

    public function profileInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record(auth()->user())
            ->schema([
                InfoSection::make('Personal Information')
                    ->schema([
                        TextEntry::make('prefix')->label('Prefix'),
                        TextEntry::make('name')->label('Full Name'),
                        TextEntry::make('gender')->label('Gender'),
                        TextEntry::make('dob')->label('Date of Birth')->date(),
                    ])->columns(2),

                InfoSection::make('Contact Details')
                    ->schema([
                        TextEntry::make('phone')->label('Mobile'),
                        TextEntry::make('landline')->label('Landline')->placeholder('N/A'),
                        TextEntry::make('email')->label('Official Email ID'),
                    ])->columns(3),

                InfoSection::make('Official Information')
                    ->schema([
                        TextEntry::make('employee_code')->label('Employee Code'),
                        TextEntry::make('username')->label('Username'),
                        TextEntry::make('designation.name')->label('Designation'),
                        TextEntry::make('centreRel.name')->label('Centre'),
                    ])->columns(2),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Personal Information')
                    ->schema([
                        Select::make('prefix')
                            ->options(['Mr' => 'Mr', 'Ms' => 'Ms', 'Mrs' => 'Mrs', 'Dr' => 'Dr'])
                            ->required(),
                        TextInput::make('name')
                            ->required(),
                        Select::make('gender')
                            ->options(['Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other'])
                            ->required(),
                        DatePicker::make('dob')
                            ->label('Date of Birth')
                            ->displayFormat('d/m/Y')
                            ->required()
                            ->native(false)
                            ->maxDate(now()->subYears(18))
                            ->minDate(now()->subYears(60))
                            ->validationMessages([
                                'before_or_equal' => 'Age must be at least 18 years.',
                                'after_or_equal' => 'Age cannot exceed 60 years.',
                            ])->required(),
                    ])->columns(2),

                Section::make('Contact Details')
                    ->schema([
                        TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(10),
                        TextInput::make('landline')
                            ->tel()
                            ->maxLength(15),
                        TextInput::make('email')
                            ->label('Official Email ID')
                            ->email()
                            ->required()
                            // ->disabled()
                            ->dehydrated(),
                    ])->columns(3),

                Section::make('Official Information')
                    ->schema([
                        TextInput::make('employee_code')
                            ->label('Employee Code')
                            ->disabled()
                            ->dehydrated(),
                        Select::make('designation_id')
                            ->label('Designation')
                            ->relationship('designation', 'name')
                            // ->disabled()
                            ->dehydrated(),
                        Select::make('centre_id')
                            ->label('Centre')
                            ->relationship('centre', 'name')
                            // ->disabled()
                            ->dehydrated(),
                        TextInput::make('username')
                            ->readOnly(),
                    ])->columns(2),

                Section::make('Security')
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->label('New Password (Leave blank to keep current)'),
                    ])
            ])
            ->model(auth()->user())
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label('Edit Profile')
                ->icon('heroicon-m-pencil-square')
                ->color('info')
                ->hidden(fn() => $this->isEditing)
                ->action(fn() => $this->isEditing = true),

            Action::make('view')
                ->label('View Profile')
                ->icon('heroicon-m-eye')
                ->color('gray')
                ->visible(fn() => $this->isEditing)
                ->action(fn() => $this->isEditing = false),
        ];
    }

    protected function getFormActions(): array
    {
        return $this->isEditing ? [
            Action::make('save')
                ->label('Update Profile')
                ->color('primary')
                ->submit('save'),
        ] : [];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            auth()->user()->update($data);
            $this->isEditing = false;
            Notification::make()->title('Profile updated successfully!')->success()->send();
        } catch (\Exception $e) {
            Notification::make()->title('Update failed')->body($e->getMessage())->danger()->send();
        }
    }
}