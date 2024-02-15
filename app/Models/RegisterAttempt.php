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
        $body = $this->attributes['body'];
        $body = json_decode($body);

        $res = new \stdClass();
        $res->store = $body->store;
        $res->firstname = $body->firstname;
        $res->lastname = $body->lastname;
        $res->email = $body->email;

        return $res;
    }
}
