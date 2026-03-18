<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperApprovalChain extends Model
{
    use HasFactory;

    protected $table = 'paper_approval_chains';

    protected $fillable = [
        'examID',
        'role_id',
        'special_role_type',
        'approval_order',
    ];

    public function examination()
    {
        return $this->belongsTo(Examination::class, 'examID', 'examID');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
