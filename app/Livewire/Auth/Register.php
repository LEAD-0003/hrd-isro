<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Register extends Component
{
    public $prefix, $name, $gender, $phone, $employee_code, $designation;
    public $centre, $dob, $email, $username, $password, $password_confirmation;

    protected $rules = [
        'prefix' => 'required',
        'name' => 'required|min:3',
        'gender' => 'required',
        'phone' => 'required|numeric|digits:10',
        'employee_code' => 'required',
        'designation' => 'required',
        'centre' => 'required',
        'dob' => 'required|date',
        'email' => [
               'required',
               'email',
               'unique:users,email',
               'regex:/^[a-zA-Z0-9._%+-]+@(isro\.gov\.in|vssc\.gov\.in|sdsc\.gov\.in|ursc\.gov\.in)$/i'
             ], 
        'username' => 'required|min:4|unique:users,username',
        'password' => 'required|min:6|confirmed',
    ];

    public function register()
    {
        $this->validate();

        User::create([
            'prefix' => $this->prefix,
            'name' => $this->name,
            'gender' => $this->gender,
            'employee_code' => $this->employee_code,
            'designation' => $this->designation,
            'centre' => $this->centre,
            'email' => $this->email,
            'phone' => $this->phone,
            'dob' => $this->dob,
            'username' => $this->username,
            'password' => Hash::make($this->password), 
            'role' => 'employee', 
            'is_active' => true,  
        ]);
       
        session()->flash('message', 'Account created successfully!');
        return redirect()->to('/');
    }
    public function render()
    {
        return view('livewire.auth.register')->layout('components.layouts.auth');
    }
}
