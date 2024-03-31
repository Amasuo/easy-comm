<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    const SEARCHABLE = [
        'name',
    ];

    protected $fillable = [
        'name',
    ];

    public function users() {
        return $this->belongsToMany(User::class, 'role_store_user')
            ->withTimestamps();
    }
}
