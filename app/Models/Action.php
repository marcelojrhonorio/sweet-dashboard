<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Action extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'path_image',
        'description',
        'grant_points',
        'action_category_id',
        'action_type_id',
        'order',
        'enabled',
        'filter_ddd',
        'filter_gender',
        'filter_cep',
        'filter_operation_begin',
        'filter_age_begin',
        'filter_operation_end',
        'filter_age_end',
        'exchange_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * Get the category for the action.
     */
    public function actionCategory()
    {
        return $this->belongsTo('App\Models\ActionCategory');
    }

    /**
     * Get the type for the action.
     */
    public function actionType()
    {
        return $this->belongsTo('App\Models\ActionType');
    }

    /**
     * Get the type metas for the action.
     */
    public function actionTypeMetas()
    {
        return $this->hasMany('App\Models\ActionTypeMeta');
    }

    /**
     * Get the checkins for the action.
     */
    public function checkins()
    {
        return $this->hasMany('App\Models\Checkin');
    }
}
