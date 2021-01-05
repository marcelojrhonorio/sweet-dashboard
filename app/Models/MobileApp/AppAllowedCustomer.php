<?php

namespace App\Models\MobileApp;

use Illuminate\Database\Eloquent\Model;

class AppAllowedCustomer extends Model
{
    protected $table = "app_allowed_customers";

    protected $guarded = [
        'id',
        'created_at',
        'update_at',
    ];
}
