<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    
    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = [
        'id',
        'token',
        'points',
        'confirmed',
        'welcome',
        'confirmation_code',
        'resend_attempts',
        'created_at',
        'update_at',
        'changed_password'
    ];
}
