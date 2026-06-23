<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function employeeRecord()
    {
        return $this->hasOne(EmployeeRecords::class);
    }

    public function officialRecord()
    {
        return $this->hasOne(OfficialRecords::class);
    }

    public function transfers()
    {
        return $this->hasMany(Transfers::class);
    }

    public function getRoleDisplayAttribute(): string
    {
        $role = $this->roles->first();
        if (! $role) {
            return 'No Role Assigned';
        }

        return match ($role->name) {
            'admin'            => 'System Administrator',
            'nurse_doctor'     => 'Nurse / Doctor',
            'facility_admin'   => 'Facility Administrator',
            'district_officer' => 'District Health Officer',
            'region_officer'   => 'Regional Health Officer',
            'ministry_official'=> 'Ministry Official',
            default            => ucwords(str_replace('_', ' ', $role->name)),
        };
    }
}
