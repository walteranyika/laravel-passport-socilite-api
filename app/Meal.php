<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $fillable=["restaurant_id","name","short_description","image","price"];

    public function restaurant(){
        return $this->belongsTo('App\Restaurant');
    }
}
