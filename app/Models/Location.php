<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /** @use HasFactory<\Database\Factories\LocationFactory> */
    use HasFactory;

    protected $table = 'locations';

    protected $fillable = [
        'name', 'district', 'region'
    ];

    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }
}
