<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    protected $table = 'cart_details';

    public function product() {
    	return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
}
