<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_name',
        'version_code',
        'file_path',
        'release_notes',
    ];

    protected $casts = [
        'version_code' => 'integer',
    ];
}
