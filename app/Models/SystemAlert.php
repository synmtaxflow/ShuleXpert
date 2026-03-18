<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemAlert extends Model
{
    use HasFactory;

    protected $table = 'system_alerts';

    protected $fillable = [
        'schoolID',
        'target_user_type',
        'target_role_id',
        'target_profession_id',
        'applies_to_all',
        'alert_type',
        'message',
        'is_marquee',
        'width',
        'bg_color',
        'text_color',
        'is_bold',
        'font_size',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'applies_to_all' => 'boolean',
        'is_marquee' => 'boolean',
        'is_bold' => 'boolean',
        'is_active' => 'boolean',
    ];
}
