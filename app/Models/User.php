<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RoleName;
use App\Helpers\PermissionHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    
    const SEARCHABLE = [
        'firstname',
        'lastname',
        'email',
        'phone',
    ];

    protected $with = [
        'store'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'store_id',
        'firstname',
        'lastname',
        'email',
        'password',
        'phone',
        'is_active'
    ];

    protected $appends = [
        'is_admin',
        'is_store_admin',
        'is_store_simple',
        'role',
        'fullname',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'roles',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'bool',
    ];

    public function getFullnameAttribute(): string
    {
        return $this->firstname ? $this->firstname  . ' ' . $this->lastname : $this->lastname;
    }

    public function store() {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isStoreAdmin()
    {
        $store = Store::find($this->store_id);
        if (!$store) {
            return false;
        }
        return $this->hasRole(PermissionHelper::getAdminRoleForStore($store));
    }

    public function isStoreSimple()
    {
        $store = Store::find($this->store_id);
        if (!$store) {
            return false;
        }
        return $this->hasRole(PermissionHelper::getSimpleRoleForStore($store));
    }

    public function getIsAdminAttribute()
    {
        return $this->isAdmin();
    }

    public function getIsStoreAdminAttribute()
    {
        return $this->isStoreAdmin();
    }

    public function getIsStoreSimpleAttribute()
    {
        return $this->isStoreSimple();
    }

    public function getRegisterAttemptAttribute()
    {
        $registerAttempt = RegisterAttempt::where('user_id', $this->id)->first();
        return $registerAttempt;
    }

    public function getRoleAttribute() {
        if ($this->isAdmin()) {
            return RoleName::ADMIN;
        }
        if ($this->isStoreAdmin()) {
            return RoleName::STORE_ADMIN;
        }
        if ($this->isStoreSimple()) {
            return RoleName::STORE_SIMPLE;
        }
        return null;
    }
}
