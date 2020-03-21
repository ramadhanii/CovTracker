<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    const STATUS_POSITIVE   = 'POSITIVE';
    const STATUS_NEGATIVE   = 'NEGATIVE';

    protected $hidden = ['password'];
}
