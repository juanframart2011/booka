<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

	protected $fillable = [
        'user_email', 'user_nick', 'user_password',
    ];
}