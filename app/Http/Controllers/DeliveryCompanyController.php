<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Http\Requests\DeliveryCompanyRequest;
use App\Models\DeliveryCompany;
use Illuminate\Http\Request;

class DeliveryCompanyController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = DeliveryCompany::class;
        $this->translationName = 'delivery-company';
    }
    
    public function store(DeliveryCompanyRequest $request)
    {
        $input = $request->validated();
        $item = new $this->class();
        $item->fill($input);
        $item->save();
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $item->addMediaFromRequest('image')->toMediaCollection('main');
        }
        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    public function update(DeliveryCompanyRequest $request)
    {
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $input = $request->validated();
        if (array_key_exists('name', $input)) {
            if (GeneralHelper::valueTakenForClassAttribute($this->class, 'name', $input['name'], $this->modelId)) {
                return $this->failure(__('app.' . $this->translationName . '.name-taken'));
            }
        }
        if (array_key_exists('phone', $input)) {
            if (GeneralHelper::valueTakenForClassAttribute($this->class, 'phone', $input['phone'], $this->modelId)) {
                return $this->failure(__('app.' . $this->translationName . '.phone-taken'));
            }
        }
        $item->fill($input);
        $item->save();
        if ($request->hasFile('image')) {
            $mediaItems = $item->getMedia("*");
            foreach ($mediaItems as $mediaItem) {
                $mediaItem->delete();
            }
            $item->addMediaFromRequest('image')->toMediaCollection('main');
        }
        return $this->success(__('app.' . $this->translationName . '.updated'), $item);
    }
}
