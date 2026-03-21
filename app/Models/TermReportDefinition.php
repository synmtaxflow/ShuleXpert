<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermReportDefinition extends Model
{
    use HasFactory;

    protected $table = 'term_report_definitions';

    protected $fillable = [
        'schoolID',
        'year',
        'term',
        'exam_ids',
        'created_by',
    ];

    protected $casts = [
        'exam_ids' => 'array',
        'year' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'schoolID', 'schoolID');
    }
}
