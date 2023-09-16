<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = User::class;
        $this->translationName = 'user';
    }    
    public function store(UserRequest $request)
    {
        $input = $request->validated();
        $item = new $this->class();
        $item->fill($input);
        $item->password = bcrypt($input['password']);
        $item->save();
        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    public function update(UserRequest $request)
    {
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $input = $request->validated();
        if (array_key_exists('email', $input)) {
            $email = $input['email'];
            $existingUser = $this->class::where('email', $email)->first();
            $authUser = auth()->user();
            // @todo : update this after adding permissions (the store admin can change his employees emails)
            if ($existingUser && $authUser->id !== $existingUser->id) {
                return $this->failure(__('app.' . $this->translationName . '.email-taken'));
            }
        }
        $item->fill($input);
        if (array_key_exists('password', $input)) {
            $item->password = bcrypt($input['password']);
        }
        $item->save();
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
