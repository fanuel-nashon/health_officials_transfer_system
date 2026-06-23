<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    /** @use HasFactory<\Database\Factories\FacilityFactory> */
    use HasFactory;

    protected $table = 'facilities';

    protected $fillable = ['name', 'location_id'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function employeeRecords()
    {
        return $this->hasMany(EmployeeRecords::class);
    }

    public function officialRecords()
    {
        return $this->hasMany(OfficialRecords::class);
    }

    public function transfersFrom()
    {
        return $this->hasMany(Transfers::class, 'from_facility_id');
    }

    public function transfersTo()
    {
        return $this->hasMany(Transfers::class, 'to_facility_id');
    }
}
