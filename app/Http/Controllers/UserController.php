<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAll(Request $request)
    {
        $users = User::all();
        if (!$users) {
            return $this->failure(__('app.user.model-not-found'), HTTPHeader::NOT_FOUND);
        }

        return $this->success(__('app.user.get-all'), $users);
    }

    public function getItem(Request $request)
    {
        $this->validateId();
        $user = User::find($this->model_id);
        if (!$user) {
            return $this->failure(__('app.user.model-not-found'), HTTPHeader::NOT_FOUND);
        }

        return $this->success(__('app.user.get-one'), $user);
    }
    
    public function store(UserRequest $request)
    {
        $input = $request->validated();
        $user = new User();
        $user->fill($input);
        $user->password = bcrypt($input['password']);
        $user->save();
        return $this->success(__('app.user.created'), $user);
    }

    public function update(UserRequest $request)
    {
        $this->validateId();
        $user = User::findOrFail($this->model_id);
        $input = $request->validated();
        if (array_key_exists('email', $input)) {
            $email = $input['email'];
            $existingUser = User::where('email', $email)->first();
            $authUser = auth()->user();
            // @todo : update this after adding permissions (the store admin can change his employees emails)
            if ($existingUser && $authUser->id !== $existingUser->id) {
                return $this->failure(__('app.user.email-taken'));
            }
        }
        $user->fill($input);
        if (array_key_exists('password', $input)) {
            $user->password = bcrypt($input['password']);
        }
        $user->save();
        return $this->success(__('app.user.updated'), $user);
    }

    public function delete(Request $request)
    {
        $this->validateId();
        $user = User::findOrFail($this->model_id);
        $user->delete();
        return $this->success(__('app.user.deleted'), $user);
    }
}
