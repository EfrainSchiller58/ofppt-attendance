<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Justification extends Model
{
    protected $fillable = [
        'absence_id', 'reason', 'file_name', 'file_path',
        'file_type', 'file_size', 'status',
        'reviewed_by', 'review_note', 'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function absence()
    {
        return $this->belongsTo(Absence::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
