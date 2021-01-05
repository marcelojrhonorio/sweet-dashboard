<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerExchangedPointsSm extends Model
{
    use SoftDeletes;

    protected $table = 'sweet.customer_exchanged_points_sm';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


}
