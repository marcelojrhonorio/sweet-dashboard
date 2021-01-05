<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignField extends Model
{
    /**
     * The fields allows fill
     *
     * @var array
     */
    protected $fillable = [
        'label',
        'campaign_field_type_id',
        'campaigns_id',
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

    public function type()
    {
        return $this->belongsTo('App\Models\CampaignFieldType', 'campaign_field_type_id', 'id');
    }
}
