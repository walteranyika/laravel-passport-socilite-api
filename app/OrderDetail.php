<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable=["order_id","meal_id","meal_name","quantity","sub_total"];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function meals()
    {
       return $this->belongsTo(Meal::class);
    }
}
