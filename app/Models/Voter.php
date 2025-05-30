<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Voter extends Authenticatable
{
    protected $table = 'voters'; // Optional if table name matches "voters"

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'department',
        'student_id',
        'rfid',
        'session',
        'photo'
    ];

    protected $hidden = [
        'remember_token',
    ];

    public function getDepartmentFullAttribute()
{
    return match ($this->department) {
        'cot' => 'College of Technology',
        'coe' => 'College of Engineering',
        'ceas' => 'College of Education, Arts and Sciences',
        'cme' => 'College of Management and Entrepreneurship',
        default => 'Unknown Department',
    };
}

}
