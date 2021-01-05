<?php

namespace App\Models\PointsValidation\EmailForwarding;

use Illuminate\Database\Eloquent\Model;

class CustomersForwardingPrint extends Model
{
    protected $table = "customers_forwarding_prints";
   
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customers_forwarding_id', 
        'image',
    ];
}
