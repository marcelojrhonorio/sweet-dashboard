<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionType extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the actions for the category.
     */
    public function actions()
    {
        return $this->hasMany('App\Models\Action');
    }
}
