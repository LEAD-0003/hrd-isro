<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $login = ''; 
    public $password = '';

    protected function rules()
    {
        return [
            'login' => 'required|string',
            'password' => 'required',
        ];
    }

    public function authenticate()
    {
        $this->validate();

        $fieldType = filter_var($this->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$fieldType => $this->login, 'password' => $this->password])) {
            
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect('/admin');
            } elseif ($user->role === 'coordinator') {
                return redirect('/coordinator'); 
            } elseif ($user->role === 'employee') {
                return redirect('/employee');
            } elseif ($user->role === 'hq') {
                return redirect('/hq');
            }
        }

        $this->addError('login', 'These credentials do not match our records.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('components.layouts.auth');
    }
}