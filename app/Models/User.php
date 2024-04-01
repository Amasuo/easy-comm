<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    const SEARCHABLE = [
        'firstname',
        'lastname',
        'email',
        'phone',
    ];

    protected $with = [
        'stores',
        'language',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'language_id',
        'firstname',
        'lastname',
        'email',
        'password',
        'phone',
        'street',
        'state',
        'city',
        'is_active'
    ];

    protected $appends = [
        'is_admin',
        'is_store_admin',
        'fullname',
        'store', // main store
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

    public function roles() {
        return $this->belongsToMany(Role::class, 'role_store_user')
            ->withTimestamps();
    }

    public function assignAdminRole()
    {
        $roleStoreUser = new RoleStoreUser();
        $roleStoreUser->role_id = 1; // admin
        $roleStoreUser->store_id = null;
        $roleStoreUser->user_id = $this->id;
        $roleStoreUser->save();
    }

    public function stores() {
        return $this->belongsToMany(Store::class, 'role_store_user')
            ->withTimestamps();
    }

    public function addStore(Store $store, $isAdmin = false)
    {
        $roleStoreUser = new RoleStoreUser();
        $roleStoreUser->role_id = $isAdmin ? 2 : 3;
        $roleStoreUser->store_id = $store->id;
        $roleStoreUser->user_id = $this->id;
        $roleStoreUser->save();
    }

    public function language() {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function isAdmin()
    {
        $roleUser = RoleStoreUser::where('user_id', $this->id)->where('role_id', 1)->first();
        return !is_null($roleUser);
    }

    public function isStoreAdmin() 
    {
        $parentStore = $this->store;
        if ($parentStore) {
            $roleStoreUser = RoleStoreUser::where('user_id', $this->id)
            ->where('store_id', $parentStore->id)
            ->where('role_id', 2)   // store admin role
            ->first();
            return !is_null($roleStoreUser);
        }
        return false;
    }

    public function getStoreAttribute()
    {
        $store = $this->stores()->first();

        return $store->parent ?? $store;
    }

    public function getIsAdminAttribute()
    {
        return $this->isAdmin();
    }

    public function getIsStoreAdminAttribute()
    {
        return $this->isStoreAdmin();
    }

    public function getRegisterAttemptAttribute()
    {
        $registerAttempt = RegisterAttempt::where('user_id', $this->id)->first();
        return $registerAttempt;
    }

    //get users for store admin
    public function getRelatedUsersQuery()
    {
        $storeIds = $this->getRelatedStoresQuery()->pluck('id');
        $usersIds = RoleStoreUser::whereIn('store_id', $storeIds)->pluck('user_id');
        return User::whereIn('id', $usersIds);
    }

    //get stores for store admin
    public function getRelatedStoresQuery()
    {
        $parentStore = $this->store;
        return Store::where('id', $parentStore->id) // parent
            ->orWhere('parent_id', $parentStore->id); // plus children
    }
}
