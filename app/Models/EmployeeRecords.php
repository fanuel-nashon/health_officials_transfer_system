<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRecords extends Model
{
    protected $table = 'employee_records';

    protected $fillable = ['user_id', 'title', 'facility_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
