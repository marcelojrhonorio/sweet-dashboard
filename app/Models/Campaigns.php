<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaigns extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'campaigns';
    /**
     * The table primary key name
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * The fields allows fill
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'title',
        'question',
        'path_image',
        'path_thumbnail',
        'status',
        'mobile',
        'desktop',
        'postback_url',
        'config_page',
        'config_email',
        'visualized',
        'id_has_offers',
        'campaign_types_id',
        'companies_id',
        'user_id_created',
        'user_id_updated',
        'filter_ddd',
        'filter_gender',
        'filter_cep',
        'filter_operation_begin',
        'filter_age_begin',
        'filter_operation_end',
        'filter_age_end',
        'order',
    ];

    /**
     * @var array
     */
    private static $campaignRelations = [
        'clickout',
        'companies',
        'clusters',
        'domains',
        'types',
        'fields.type',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clickout() :\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\CampaignsClickout', 'campaigns_id', 'id')->orderBy('affirmative','DESC');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany('App\Models\CampaignAnswers', 'campaign_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companies() :\Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Companies','companies_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function types() :\Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\CampaignTypes','campaign_types_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function domains() :\Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        //return $this->belongsToMany('App\Models\Domains', 'campaigns_has_domains', 'domains_id', 'campaigns_id');
       // return $this->belongsToMany('App\Models\Domains', 'campaigns_has_domains','domains_id', 'campaigns_id');
        return $this->belongsToMany('App\Models\Domains', 'campaigns_has_domains','campaigns_id', 'domains_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function clusters() :\Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        //$this->belongsToMany('relacao', 'nome da tabela pivot', 'key ref. clusters em pivot', 'key ref. campaigns em pivot')
        //return $this->belongsToMany('App\Models\Clusters', 'campaigns_has_clusters', 'clusters_id','campaigns_id');
        return $this->belongsToMany('App\Models\Clusters', 'campaigns_has_clusters', 'campaigns_id', 'clusters_id');
    }

    public function fields()
    {
        return $this->hasMany('App\Models\CampaignField', 'campaign_id', 'id');
    }

    static public function getCampaignRelations()
    {
        return self::$campaignRelations;
    }

    public static function search($request)
    {
        try {
            $campaign = Campaigns::with(self::getCampaignRelations());

            return $campaign;
        } catch (\Exception $e) {
            return new Campaigns();
        }
    }
}
