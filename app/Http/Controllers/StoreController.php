<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Helpers\GeneralHelper;
use App\Http\Requests\StoreRequest;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function getAll(Request $request)
    {
        $stores = Store::all();
        if (!$stores) {
            return $this->failure(__('app.store.model-not-found'), HTTPHeader::NOT_FOUND);
        }

        return $this->success(__('app.store.get-all'), $stores);
    }

    public function getItem(Request $request)
    {
        $this->validateId();
        $store = Store::find($this->model_id);
        if (!$store) {
            return $this->failure(__('app.store.model-not-found'), HTTPHeader::NOT_FOUND);
        }

        return $this->success(__('app.store.get-one'), $store);
    }
    
    public function store(StoreRequest $request)
    {
        $input = $request->validated();
        $store = new Store();
        $store->fill($input);
        $store->save();
        return $this->success(__('app.store.created'), $store);
    }

    public function update(StoreRequest $request)
    {
        $this->validateId();
        $store = Store::findOrFail($this->model_id);
        $input = $request->validated();
        if (array_key_exists('name', $input)) {
            if (GeneralHelper::valueTakenForClassAttribute(Store::class, 'name', $input['name'], $this->model_id)) {
                return $this->failure(__('app.store.name-taken'));
            }
        }
        $store->fill($input);
        $store->save();
        return $this->success(__('app.store.updated'), $store);
    }

    public function delete(Request $request)
    {
        $this->validateId();
        $store = Store::findOrFail($this->model_id);
        $store->delete();
        return $this->success(__('app.store.deleted'), $store);
    }
}
