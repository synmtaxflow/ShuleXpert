<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperApprovalLog extends Model
{
    use HasFactory;

    protected $table = 'paper_approval_logs';

    protected $fillable = [
        'exam_paperID',
        'role_id',
        'special_role_type',
        'approval_order',
        'status',
        'approved_by',
        'comment',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function examPaper()
    {
        return $this->belongsTo(ExamPaper::class, 'exam_paperID', 'exam_paperID');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(Teacher::class, 'approved_by', 'id');
    }
}
