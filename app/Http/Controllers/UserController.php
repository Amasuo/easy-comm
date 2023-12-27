<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Enums\RoleName;
use App\Helpers\GeneralHelper;
use App\Helpers\PermissionHelper;
use App\Http\Requests\UserRequest;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = User::class;
        $this->translationName = 'user';
    }

    public function getCurrent(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return $this->failure(__('app.' . $this->translationName . '.current-failure'), HTTPHeader::NOT_FOUND);
        }

        return $this->success(__('app.' . $this->translationName . '.current-success'), $user);
    }

    public function getAll(Request $request)
    {
        $user = auth()->user();
        $data = null;
        if ($user->isAdmin()) {
            $data = $this->class::query();
        } else {
            $data = $this->class::where('store_id', $user->store_id);
        }

        $searchQuery = $request->query('search');
        $searchableAttributes = $this->class::SEARCHABLE;
        if ($searchQuery && $searchableAttributes) {
            $data = $data->where(function ($query) use ($searchQuery, $searchableAttributes) {
                foreach ($searchableAttributes as $searchableAttribute) {
                    $query = $query->orWhere($searchableAttribute, 'like', '%' . $searchQuery . '%');
                }
            });
        }
        $data = $data->paginate(10);
        if (!$data) {
            return $this->failure(__('app.' . $this->translationName . '.model-not-found'), HTTPHeader::NOT_FOUND);
        }

        return $this->success(__('app.' . $this->translationName . '.get-all'), $data);
    }

    public function store(UserRequest $request)
    {
        $user = auth()->user();
        $input = $request->validated();
        $item = new $this->class();
        $item->fill($input);
        $item->password = bcrypt($input['password']);
        if ($user->isAdmin()) {
            $item->store_id = array_key_exists('store_id', $input) ? $input['store_id'] : null;
        } else {
            $item->store_id = $user->store_id;
        }
        $item->save();

        if (array_key_exists('role', $input) && ($user->isAdmin()  || $user->isStoreAdmin())) {
            $role = $input['role'];
            $store = Store::find($item->store_id);
            if ($role == RoleName::ADMIN && $user->isAdmin()) {
                PermissionHelper::assignUserRole($item, 'admin');
            } else if ($role == RoleName::STORE_ADMIN && $store) {
                PermissionHelper::assignUserRole($item, PermissionHelper::getAdminRoleForStore($store));
            } else if ($store) {
                $store = Store::find($item->store_id);
                PermissionHelper::assignUserRole($item, PermissionHelper::getSimpleRoleForStore($store));
            }
        }

        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    public function update(UserRequest $request)
    {
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $input = $request->validated();
        $authUser = auth()->user();

        //check email taken
        if (array_key_exists('email', $input)) {
            $email = $input['email'];
            $existingUser = $this->class::where('email', $email)->first();
            if (GeneralHelper::valueTakenForClassAttribute($this->class, 'email', $email, $this->modelId)) {
                return $this->failure(__('app.' . $this->translationName . '.email-taken'));
            }
        }
        
        $item->fill($input);

        if (array_key_exists('password', $input)) {
            $item->password = bcrypt($input['password']);
        }

        if (!$item->store_id && $authUser->isAdmin()) {
            $item->store_id = array_key_exists('store_id', $input) ? $input['store_id'] : $item->store_id;
        }
        $item->save();

        $isStoreAdminMinimum = $authUser->isAdmin()  || $authUser->isStoreAdmin();
        if (array_key_exists('role', $input) && ($isStoreAdminMinimum)) {
            $role = $input['role'];
            $store = Store::find($item->store_id);
            if ($role == RoleName::ADMIN && $authUser->isAdmin()) {
                PermissionHelper::assignUserRole($item, 'admin');
            } else if ($role == RoleName::STORE_ADMIN && $isStoreAdminMinimum && $store) {
                PermissionHelper::assignUserRole($item, PermissionHelper::getAdminRoleForStore($store));
            } else if ($store) {
                $store = Store::find($item->store_id);
                PermissionHelper::assignUserRole($item, PermissionHelper::getSimpleRoleForStore($store));
            }
        }

        return $this->success(__('app.' . $this->translationName . '.updated'), $item);
    }

    public function assignRole(Request $request)
    {
        $this->validateId();
        $user = $this->class::findOrFail($this->modelId);

        $roleId = $request->roleId;
        $role = Role::findOrFail($roleId);

        $user->assignRole($role);

        return $this->success(__('app.' . $this->translationName . '.role.assigned'), $user);
    }

    public function removeRole(Request $request)
    {
        $this->validateId();
        $user = $this->class::findOrFail($this->modelId);

        $roleId = $request->roleId;
        $role = Role::findOrFail($roleId);

        $user->removeRole($role);
        
        return $this->success(__('app.' . $this->translationName . '.role.removed'), $user);
    }
}
