<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    /** @use HasFactory<\Database\Factories\FacilityFactory> */
    use HasFactory;

    protected $table = 'facilities';

    protected $fillable =[
        'name'
    ];

    public function employeeRecords()
    {
        return $this->hasMany(EmployeeRecords::class);
    }

    public function officialRecords()
    {
        return $this->hasMany(OfficialRecords::class);
    }
}
