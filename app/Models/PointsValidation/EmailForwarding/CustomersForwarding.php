<?php

namespace App\Models\PointsValidation\EmailForwarding;

use Illuminate\Database\Eloquent\Model;

class CustomersForwarding extends Model
{
    protected $table = "customers_forwarding";

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'email_forwarding_id', 
        'customers_id',
        'won_points',
    ];
}
