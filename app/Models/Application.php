<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    protected $fillable = [
        'application_ref', 'user_id', 'short_course_id', 'surname', 'first_name', 'other_name', 
        'email', 'phone', 'gender', 'date_of_birth', 'address', 'country', 'state', 'lga', 
        'highest_qualification', 'ssce_type', 'ssce_year', 'ssce_exam_number', 
        'degree_type', 'degree_institution', 'degree_year', 'degree_class',
        'amount', 'payment_status', 'payment_receipt_path', 'payment_rrr',
        'admission_status', 'locale'
    ];

    public function course()
    {
        return $this->belongsTo(ShortCourse::class, 'short_course_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
