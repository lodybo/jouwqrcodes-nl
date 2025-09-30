<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Random\RandomException;

class QrCode extends Model
{
    use HasFactory;
    protected $fillable = ['shortcode', 'destination_url', 'title'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
