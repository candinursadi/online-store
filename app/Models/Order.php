<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public function detail() {
        return $this->hasMany('App\Models\OrderDetail', 'order_id', 'id');
    }
}
