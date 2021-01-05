<?php

namespace App\Models\RelationshipRules;

use Illuminate\Database\Eloquent\Model;

class RelationshipRuleCustomers extends Model
{
    //
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'relationship_rule_id', 'customer_id',
    ];
}
