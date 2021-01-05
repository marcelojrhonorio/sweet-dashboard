<?php

namespace App\Models\ResearchesSponsored;

use Illuminate\Database\Eloquent\Model;

class ResearchesMiddlePage extends Model
{
    protected $connection= 'researches_mysql';
    protected $table = 'researches_middle_pages';

    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = [
        'id',
        'created_at',
        'update_at',
    ];
}