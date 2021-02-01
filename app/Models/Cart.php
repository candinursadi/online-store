<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    public function detail() {
        return $this->hasMany('App\Models\CartDetail', 'cart_id', 'id')->whereNull('is_deleted');
    }
}
