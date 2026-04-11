<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Training extends Model
{
    // protected $guarded = [];
    
    protected $fillable = [
        'type', 
        'title',
        'state_id',
        'training_institute',
        'start_date', 
        'end_date', 
        'max_participants', 
        'seat_distribution',
        'mode',
        'last_date_to_apply',
        'target_designations',
        'training_type_id',
        'attachments',
        'location',
    ];

    protected $casts = [
        'seat_distribution' => 'array',
        'target_designations' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'attachments' => 'array',
    ];
    // protected $casts = [
    //     'seat_distribution' => 'json', 
    //     'target_designations' => 'json', 
    //     'start_date' => 'date',
    //     'end_date' => 'date',
    //     'attachments' => 'array',
    //     'seat_distribution' => 'array',

    // ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trainingType()
    {
        return $this->belongsTo(TrainingType::class, 'training_type_id');
    }

    public function applications()
    {
        return $this->hasMany(TrainingApplication::class, 'training_id');
    }

    public function feedbacks()
    {
        return $this->hasManyThrough(
            TrainingFeedback::class,
            TrainingApplication::class,
            'training_id', 
            'training_application_id' 
        );
    }

   private $centreStatsCache = null;

    public function centreStats()
    {
        if ($this->centreStatsCache !== null) {
            return $this->centreStatsCache;
        }

        return $this->centreStatsCache = DB::table('training_applications')
            ->select(
                'centre',
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(status = 'submitted') as submitted"),
                DB::raw("SUM(status = 'approved') as approved"),
                DB::raw("SUM(status = 'completed') as completed")
            )
            ->where('training_id', $this->id)
            ->groupBy('centre')
            ->get()
            ->keyBy('centre');
    }
}