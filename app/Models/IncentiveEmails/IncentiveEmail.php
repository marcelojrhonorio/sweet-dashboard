<?php

namespace App\Models\IncentiveEmails;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncentiveEmail extends Model
{
    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = [
        'id',
        'created_at',
        'update_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     */
    protected $dates = [
        'deleted_at',
    ];    
}
