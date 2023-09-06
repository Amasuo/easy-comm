<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductOptionRequest;
use App\Http\Requests\ProductOptionValueRequest;
use App\Models\ProductOptionValue;
use Illuminate\Http\Request;

class ProductOptionValueController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = ProductOptionValue::class;
        $this->translationName = 'product-option-value';
    }
    
    public function store(ProductOptionValueRequest $request)
    {
        $input = $request->validated();
        $item = new $this->class();
        $item->fill($input);
        $item->save();
        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    public function update(ProductOptionValueRequest $request)
    {
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $input = $request->validated();
        $item->fill($input);
        $item->save();
        return $this->success(__('app.' . $this->translationName . '.updated'), $item);
    }
}
