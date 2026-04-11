<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'reset_title',
        'reset_color',
        'reset_body',
        'pub_title',
        'pub_color',
        'pub_body',
        'nom_title',
        'nom_color',
        'nom_body',
        'app_title',
        'app_color',
        'app_body',
        'rem_title',
        'rem_color',
        'rem_body',
    ];
}