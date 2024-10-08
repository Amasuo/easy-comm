<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegisterAttempt extends Model
{
    use HasFactory;

    protected $table = 'register_attempts';

    protected $fillable = [
        'user_id',
        'body',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getBodyAttribute()
    {
        return json_decode($this->attributes['body']);
    }
}
