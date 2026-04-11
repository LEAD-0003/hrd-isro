<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingApplication extends Model
{
    protected $fillable = [
        'user_id',
        'training_id',
        
        'nominee_emp_id',
        'nominee_name',
        'nominee_designation',
        'nominee_email',
        'nominee_phone',
        
        'is_self_apply', 
        'centre',        
        'year',
        'status',
        
        'hq_comments',
        
        'feedback_rating',
        'feedback_comments',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }
    public function centreRel(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Centre::class, 'centre');
    }
    public function feedback()
    {
        return $this->hasOne(TrainingFeedback::class, 'training_application_id');
    }
   
}