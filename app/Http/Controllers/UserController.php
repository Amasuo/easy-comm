<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Helpers\GeneralHelper;
use App\Http\Requests\UserRequest;
use App\Models\RoleStoreUser;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

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
            $data = $user->getRelatedUsersQuery();
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

        // check if user is not an admin and wants to add an admin user
        if (($input['role_id'] == 1) && !$user->isAdmin()) {
            abort(HTTPHeader::FORBIDDEN, __('unauthorized'));
        }
        
        $item = new $this->class();
        $item->fill($input);
        $item->password = bcrypt($input['password']);
        $item->save();

        if (($input['role_id'] == 1)) { // admin role
            $roleStoreUser = new RoleStoreUser();
                $roleStoreUser->role_id = $input['role_id'];
                $roleStoreUser->store_id = null;
                $roleStoreUser->user_id = $item->id;
                $roleStoreUser->save();
        } elseif (($input['role_id'] == 2)) { // store admin role
            $parentStore = null;
            if ($user->isAdmin()) {
                if (array_key_exists('store_ids', $input)) {
                    $store = Store::whereIn('id', $input['store_ids'])->first();
                    $parentStore = $store->parent ?? $store;
                }
            } else { // store admin
                $parentStore = $user->store;
            }
            
            if ($parentStore) {
                $roleStoreUser = new RoleStoreUser();
                $roleStoreUser->role_id = $input['role_id'];
                $roleStoreUser->store_id = $parentStore->id;
                $roleStoreUser->user_id = $item->id;
                $roleStoreUser->save();
            }
        } elseif ($input['role_id'] == 3) { // store simple role
            if (array_key_exists('store_ids', $input)) {
                foreach ($input['store_ids'] as $storeId) {
                    $roleStoreUser = new RoleStoreUser();
                    $roleStoreUser->role_id = $input['role_id'];
                    $roleStoreUser->store_id = $storeId;
                    $roleStoreUser->user_id = $item->id;
                    $roleStoreUser->save();
                }
            }
        }

        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    public function update(UserRequest $request)
    {
        $user = auth()->user();
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $input = $request->validated();
        $authUser = auth()->user();

        //check email taken
        if (array_key_exists('email', $input)) {
            $email = $input['email'];
            if (GeneralHelper::valueTakenForClassAttribute($this->class, 'email', $email, $this->modelId)) {
                return $this->failure(__('app.' . $this->translationName . '.email-taken'));
            }
        }
        
        $item->fill($input);

        if (array_key_exists('password', $input)) {
            $item->password = bcrypt($input['password']);
        }

        $item->save();

        // check if user is not an admin and wants to add an admin user
        if (array_key_exists('role_id', $input) && ($input['role_id'] == 1) && !$authUser->isAdmin()) {
            abort(HTTPHeader::FORBIDDEN, __('unauthorized'));
        }

        if (array_key_exists('role_id', $input) && $user->isStoreAdmin()) {
            // delete old
            RoleStoreUser::where('user_id', $item->id)->delete();

            if (($input['role_id'] == 2)) { // store admin role
                $parentStore = $user->store;
                if ($parentStore) {
                    $roleStoreUser = new RoleStoreUser();
                    $roleStoreUser->role_id = $input['role_id'];
                    $roleStoreUser->store_id = $parentStore->id;
                    $roleStoreUser->user_id = $item->id;
                    $roleStoreUser->save();
                }
            } elseif ($input['role_id'] == 3) { // store simple role
                if (array_key_exists('store_ids', $input)) {
                    foreach ($input['store_ids'] as $storeId) {
                        $roleStoreUser = new RoleStoreUser();
                        $roleStoreUser->role_id = $input['role_id'];
                        $roleStoreUser->store_id = $storeId;
                        $roleStoreUser->user_id = $item->id;
                        $roleStoreUser->save();
                    }
                }
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
