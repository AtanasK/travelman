<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

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

    public function locations()
    {
        return $this->hasMany(Location::class);
    }


    public function addLocation($destination)
    {
        $this->locations()->create(compact('destination'));
    }

    public function updateLocation($destination){

    }

    public function deleteLocation($location){

    }
}
