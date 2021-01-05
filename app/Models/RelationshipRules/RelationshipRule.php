<?php

namespace App\Models\RelationshipRules;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RelationshipRule extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = [
        'id',
        'created_at',
        'update_at',
    ];
}
