<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    //use Enums;
    protected $fillable=["driver_id","restaurant_id","customer_id","address","total","status","picked_at"];
}
