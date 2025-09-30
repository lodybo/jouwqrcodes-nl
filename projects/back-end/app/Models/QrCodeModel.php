<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Random\RandomException;

/**
 * @property int $id
 * @property string $shortcode
 * @property string $destination_url
 * @property string|null $title
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|QrCodeModel where($column, $operator = null, $value = null)
 * @method static QrCodeModel|null first()
 * @method static QrCodeModel create(array $attributes)
 */
class QrCodeModel extends Model
{
    use HasFactory;

    protected $table = 'qr_codes';
    protected $fillable = ['shortcode', 'destination_url', 'title'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
