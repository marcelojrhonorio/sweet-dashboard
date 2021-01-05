<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignFieldType extends Model
{
    /**
     * The fields allows fill
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * The fields does not allows fill
     *
     * @var array
     */
    protected $guarded = [
        'id',        
        'created_at',
        'update_at',
    ];    
}
