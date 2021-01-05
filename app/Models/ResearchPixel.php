<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchPixel extends Model
{
    /**
     * The attributes that aren't mass assignable.
     */
    protected $table = 'research_pixels';

    protected $fillable = [
        'id',
        'research_id',
        'affiliate_id',
        'type',
        'goal_id',
        'has_redirect',
        'link_redirect',
        'created_at',
        'update_at',
    ];
}
