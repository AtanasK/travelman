<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForgotPassword extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
