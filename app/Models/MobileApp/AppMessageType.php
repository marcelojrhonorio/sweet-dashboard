<?php

namespace App\Models\MobileApp;

use Illuminate\Database\Eloquent\Model;

class AppMessageType extends Model
{
    protected $table = "app_message_types";

    protected $guarded = [
        'id',
        'created_at',
        'update_at',
    ];

}
