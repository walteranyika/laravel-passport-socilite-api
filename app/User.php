<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    //https://medium.com/@hivokas/api-authentication-via-social-networks-for-your-laravel-application-d81cfc185e60
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function linkedSocialAccounts()
    {
        return $this->hasMany(LinkedSocialAccount::class);
    }

    public function customer(){
        return $this->hasOne(Customer::class);
    }

    public function driver(){
        return $this->hasOne(Driver::class);
    }
    public function restaurants(){
        return $this->hasMany(Restaurant::class);
    }
    /*Client ID: 1
        Client secret: XNI94tI0gsnGJfGnPWB3ACbqWouQgODTlKpKNBXB
        Password grant client created successfully.
        Client ID: 2
        Client secret: EH8m3WHjaDCTvsocT3JG74LVRUFloRZdizvilig8
      */
}
