<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable=["user_id","avatar","phone","address","location"];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
