<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfers extends Model
{
    protected $table = 'transfers';

    protected $fillable = [
        'level', 'level_status', 
    ];
}
