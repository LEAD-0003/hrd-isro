<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use Illuminate\Support\Facades\Mail;
use App\Mail\TrainingPublishedMail;
use App\Http\Controllers\FeedbackPdfController;
use App\Livewire\Auth\ResetPassword; 

Route::get('/', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::get('/forgot-password', ForgotPassword::class)->name('password.request');


Route::get('/reset-password', ResetPassword::class)->name('password.reset');

Route::get('/test-mail', function () {
    $training = \App\Models\Training::first(); 
    $allottedSeats = 10;

    Mail::to('reachawave.bala@gmail.com')->send(new TrainingPublishedMail($training, $allottedSeats));

    return "Mail Sent Successfully!";
});

Route::get('/admin/feedback-pdf', [FeedbackPdfController::class, 'download'])
      ->middleware(['auth'])->name('feedback.pdf');