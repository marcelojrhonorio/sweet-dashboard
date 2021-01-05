<?php

namespace App\Models\MobileApp;

use Illuminate\Database\Eloquent\Model;

class AppMessage extends Model
{
    protected $table = "app_messages";

    protected $guarded = [
        'id',
        'created_at',
        'update_at',
    ];
}
