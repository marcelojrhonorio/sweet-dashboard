<?php

namespace App\Models\ResearchesSponsored;

use Illuminate\Database\Eloquent\Model;

class ResearchQuestions extends Model
{
    protected $connection= 'researches_mysql';
    protected $table = 'researche_questions';

    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = [
        'id',
        'created_at',
        'update_at',
    ];
}