<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable=["user_id","name","phone","address","logo"];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function meals(){
        return $this->hasMany("App\Meal");
    }
}