<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfers extends Model
{
    protected $table = 'transfers';

    protected $fillable = [
        'user_id', 'from_facility_id', 'to_facility_id',
        'reason', 'level', 'level_status',
        'facility_comment', 'district_comment', 'region_comment', 'ministry_comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromFacility()
    {
        return $this->belongsTo(Facility::class, 'from_facility_id');
    }

    public function toFacility()
    {
        return $this->belongsTo(Facility::class, 'to_facility_id');
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->level_status === 'rejected') {
            return 'Rejected at ' . ucfirst($this->level) . ' level';
        }
        if ($this->level === 'ministry' && $this->level_status === 'approved') {
            return 'Approved';
        }

        return 'Pending – ' . ucfirst($this->level) . ' Review';
    }

    public function getStatusColorAttribute(): string
    {
        return match (true) {
            $this->level_status === 'rejected'                                        => 'red',
            $this->level === 'ministry' && $this->level_status === 'approved'         => 'green',
            default                                                                   => 'yellow',
        };
    }

    public function isFinallyApproved(): bool
    {
        return $this->level === 'ministry' && $this->level_status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->level_status === 'rejected';
    }

    public function isActive(): bool
    {
        return ! $this->isFinallyApproved() && ! $this->isRejected();
    }
}
