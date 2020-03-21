<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    const STATUS_ACTIVE   = 'ACTIVE';
    const STATUS_INACTIVE   = 'INACTIVE';

    public function user(){
        return $this->belongsTo("App\Models\User", "user_id");
    }
}
