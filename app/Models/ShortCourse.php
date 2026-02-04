<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortCourse extends Model
{
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'category',
        'course_name',
        'code',
        'fee',
        'duration',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
    //
}
