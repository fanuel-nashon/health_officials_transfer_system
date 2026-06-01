<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRecords extends Model
{
    protected $table = 'employee_records';

    protected $fillable = [
        'title'
    ];
}
