<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    //use Enums;
    protected $fillable=["driver_id","restaurant_id","customer_id","address","total","status","picked_at"];

    public function order_details(){
        return $this->hasMany(OrderDetail::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
