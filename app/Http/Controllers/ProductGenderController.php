<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Http\Requests\ProductGenderRequest;
use App\Models\ProductGender;
use Illuminate\Http\Request;

class ProductGenderController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = ProductGender::class;
        $this->translationName = 'product-gender';
    }

    public function store(ProductGenderRequest $request)
    {
        $input = $request->validated();
        $item = new $this->class();
        $item->fill($input);
        $item->save();

        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    public function update(ProductGenderRequest $request)
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
