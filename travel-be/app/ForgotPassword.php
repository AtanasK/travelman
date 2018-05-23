<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForgotPassword extends Model
{
    protected $fillable = ['token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
