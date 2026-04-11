<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class ResetPassword extends Component
{
    public $token, $email, $password, $password_confirmation;

    public function mount()
    {
        $this->token = request()->query('token');
        $this->email = request()->query('email');

        if (! $this->token) {
            return redirect()->route('login');
        }
    }

    public function resetPassword()
    {
        $this->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker()->reset(
            [
                'token' => $this->token,
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
            ],
            function ($user, $password) {
                if ($user) {
                    $user->password = Hash::make($password);
                    $user->setRememberToken(Str::random(60));
                    $user->save();
                }
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            Notification::make()
                ->title('Password updated successfully!')
                ->success()
                ->send();

            return redirect()->route('login');
        }

        $this->addError('email', __($status));
    }

    public function render()
    {
        return view('livewire.auth.reset-password')
            ->layout('components.layouts.auth');
    }
}