<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    protected $fillable = [
        'student_id', 'teacher_id', 'group_id',
        'date', 'start_time', 'end_time',
        'subject', 'notes', 'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function justification()
    {
        return $this->hasOne(Justification::class);
    }
}
