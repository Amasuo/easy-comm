<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    use HasFactory;
    protected $table = 'languages';

    const SEARCHABLE = [
        'name',
        'short_form',
    ];

    protected $fillable = [
        'name',
        'short_form',
    ];

    protected static function booted()
    {
        static::deleting(function ($language) {
            User::where('language_id', $language->id)->update(['language_id' => null]);
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'language_id');
    }
}
