<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficialRecords extends Model
{
    protected $table = 'official_records';

    protected $fillable = ['user_id', 'title', 'facility_id', 'district', 'region'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
