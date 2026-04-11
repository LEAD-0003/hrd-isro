<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Filament\Notifications\Notification;

class ForgotPassword extends Component
{
    public $email;

    protected $rules = [
        'email' => 'required|email|exists:users,email',
    ]; 
    protected $messages = [
        'email.exists' => 'Invalid email address.',
    ];

    public function sendResetLink()
    {
        $this->validate();

        $status = \Illuminate\Support\Facades\Password::broker()->sendResetLink(
            ['email' => $this->email]
        );

        if ($status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
            // \Filament\Notifications\Notification::make()
            //     ->title('Reset link anuppiyaachu!')
            //     ->success()
            //     ->body('Check your mail for the reset link.')
            //     ->send();

            return redirect()->route('login');
        }

        $this->addError('email', __($status));
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')
            ->layout('components.layouts.auth');
    }
}