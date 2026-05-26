<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordRequest extends Model
{
    protected $fillable = [
        'email',
        'reason',
        'description',
        'status',
        'admin_note',
    ];
}
