<?php

namespace App\Models\ResearchesSponsored;

use Illuminate\Database\Eloquent\Model;

class Research extends Model
{
    protected $connection= 'researches_mysql';
    protected $table = 'researches';

    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = [
        'id',
        'created_at',
        'update_at',
    ];

    protected $fillable = [
        'title', 'subtitle', 'description', 'points', 'final_url', 'enabled',
    ];

}