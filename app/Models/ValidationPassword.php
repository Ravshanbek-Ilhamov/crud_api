<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValidationPassword extends Model
{
    public $fillable = [
        'password',
        'user_id',
    ];
}
