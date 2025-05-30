<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
    'first_name',
    'middle_name',
    'last_name',
    'position',
    'department',
    'session',
    'photo'
];

}
