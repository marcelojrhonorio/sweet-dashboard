<?php

namespace App\Models\PointsValidation\EmailForwarding;

use Illuminate\Database\Eloquent\Model;

class CustomersForwardingStatus extends Model
{
    protected $table = "customers_forwarding_status";

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'customers_id',
        'name',
        'email',
        'status',
    ];
}
