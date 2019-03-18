<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable=["order_id","meal_id","quantity","sub_total"];
}
