<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Http\Requests\DeliveryDriverRequest;
use App\Models\DeliveryDriver;
use Illuminate\Http\Request;

class DeliveryDriverController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = DeliveryDriver::class;
        $this->translationName = 'delivery-driver';
    }
    
    public function store(DeliveryDriverRequest $request)
    {
        $input = $request->validated();
        $item = new $this->class();
        $item->fill($input);
        $item->save();
        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    public function update(DeliveryDriverRequest $request)
    {
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $input = $request->validated();
        if (array_key_exists('phone', $input)) {
            if (GeneralHelper::valueTakenForClassAttribute($this->class, 'phone', $input['phone'], $this->modelId)) {
                return $this->failure(__('app.' . $this->translationName . '.phone-taken'));
            }
        }
        $item->fill($input);
        $item->save();
        return $this->success(__('app.' . $this->translationName . '.updated'), $item);
    }
}
