<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Helpers\PermissionHelper;
use App\Http\Requests\StoreRequest;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = Store::class;
        $this->translationName = 'store';
    }

    public function store(StoreRequest $request)
    {
        $input = $request->validated();
        $item = new $this->class();
        $item->fill($input);
        $item->save();

        PermissionHelper::createStoreRoles($item);

        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    public function update(StoreRequest $request)
    {
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $input = $request->validated();
        if (array_key_exists('name', $input)) {
            if (GeneralHelper::valueTakenForClassAttribute($this->class, 'name', $input['name'], $this->modelId)) {
                return $this->failure(__('app.' . $this->translationName . '.name-taken'));
            }
        }
        $item->fill($input);
        $item->save();
        return $this->success(__('app.' . $this->translationName . '.updated'), $item);
    }
}
