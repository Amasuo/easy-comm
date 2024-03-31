<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Http\Requests\RegisterAttemptRequest;
use App\Models\RegisterAttempt;
use App\Models\Store;
use Illuminate\Http\Request;

class RegisterAttemptController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = RegisterAttempt::class;
        $this->translationName = 'register-attempt';
    }
    
    public function confirm(RegisterAttemptRequest $request) {
        $this->validateId();
        $item =  $this->class::find($this->modelId);
        if (!$item) {
            return $this->failure(__('app.' . $this->translationName . '.model-not-found'), HTTPHeader::NOT_FOUND);
        }

        $input = $request->validated();
        $storeId = $input['store_id'] ?? null;
        if (!$storeId) {
            $storeName = $input['store'];
            $store = Store::where('name', $storeName)->first();
            if ($store) {
                return $this->failure(__('app.' . $this->translationName . '.model-name-exists'), HTTPHeader::UNPROCESSABLE_ENTITY);
            }
            $store = new Store();
            $store->name = $storeName;
            $store->save();
            $storeId = $store->id;
        }

        $user = $item->user;
        $user->firstname = $input['firstname'];
        $user->lastname = $input['lastname'];
        $user->phone = $input['phone'];
        $user->email = $input['email'];
        $user->is_active = true;
        $user->save();

        $user->addStore($store, isAdmin: true);

        // delete register attempt after confirmation
        $item->delete();

        return $this->success(__('app.' . $this->translationName . '.user-activated'), $user);
    }
}
