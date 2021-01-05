<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignFieldAnswer extends Model
{
    /**
     * The fields allows fill
     *
     * @var array
     */
    protected $fillable = [
        'campaign_answer_id',
        'value',
        'campaign_field_id',
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

    public function fields()
    {
        return $this->hasMany('App\Models\CampaignField');
    }
}
