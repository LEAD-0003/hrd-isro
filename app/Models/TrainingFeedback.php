<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingFeedback extends Model
{
    protected $table = 'training_feedbacks';
    protected $guarded = [];

    public function trainingApplication()
    {
        return $this->belongsTo(TrainingApplication::class);
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, TrainingApplication::class, 'id', 'id', 'training_application_id', 'user_id');
    }
}