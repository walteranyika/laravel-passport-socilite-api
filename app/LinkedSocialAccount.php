<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkedSocialAccount extends Model
{
    //https://medium.com/@hivokas/api-authentication-via-social-networks-for-your-laravel-application-d81cfc185e60
    protected $fillable = [
        'provider_name',
        'provider_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
