<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'sort_order',
        'is_active',
        'attachment',
    ];

    /**
     * Default values for attributes.
     * Default-ah sorting order 0 mattrum active status true-nu set panroam.
     */
    protected $attributes = [
        'sort_order' => 0,
        'is_active' => true,
    ];
}