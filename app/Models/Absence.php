<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    protected $fillable = [
        'student_id', 'teacher_id', 'group_id',
        'date', 'start_time', 'end_time', 'hours',
        'subject', 'notes', 'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (Absence $absence) {
            if (empty($absence->hours) && $absence->start_time && $absence->end_time) {
                $start = strtotime($absence->start_time);
                $end = strtotime($absence->end_time);
                $absence->hours = round(($end - $start) / 3600, 2);
            }
        });
    }

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
