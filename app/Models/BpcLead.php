<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpcLead extends Model
{
    protected $table = 'bpc_leads';

    protected $fillable = [
        'ip_address', 
        'sub_id',
        'browser_name', 
        'browser_family', 
        'platform_name',
        'platform_family',
        'device_family',
        'device_model',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'update_at',
    ];
}
