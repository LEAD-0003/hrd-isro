<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\TrainingApplication;
use App\Models\User;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    TrainingApplication::where('status', 'approved')
        ->whereHas('training', function ($query) {
            $query->where('end_date', '<', now()->toDateString());
        })
        ->update(['status' => 'completed']);
})->daily();

Schedule::call(function () {
    $employees = User::where('role', 'employee')
        ->where('is_active', true)
        ->whereNotNull('dob')
        ->get();

    foreach ($employees as $employee) {
        $age = Carbon::parse($employee->dob)->age;

        if ($age >= 61) {
            $employee->update(['is_active' => false]);
        }
    }
})->daily();
