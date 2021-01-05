<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'customer_id',
        'action_id',
    ];

    /**
     * Get the customer for the checkin.
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customers');
    }

    /**
     * Get the action for the checkin.
     */
    public function action()
    {
        return $this->belongsTo('App\Models\Action');
    }
}
