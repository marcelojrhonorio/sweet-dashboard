<?php

namespace App\Models\MobileApp;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $table = "app_notifications";
    
    protected $guarded = [
        'id',
        'created_at',
        'update_at',
    ];
}
