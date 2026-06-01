<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficialRecords extends Model
{
    protected $table = 'official_records';

    protected $fillable = [
        'title'
    ];
}
