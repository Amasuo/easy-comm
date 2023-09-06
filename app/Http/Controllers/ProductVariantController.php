<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductVariantRequest;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = ProductVariant::class;
        $this->translationName = 'product-variant';
    }
    
    public function store(ProductVariantRequest $request)
    {
        $input = $request->validated();
        $item = new $this->class();
        $item->fill($input);
        $item->custom_price = $request['custom_price'] ?? null;
        $item->save();
        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    public function update(ProductVariantRequest $request)
    {
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $input = $request->validated();
        $item->fill($input);
        $item->custom_price = $request['custom_price'] ?? $item->custom_price ?? null;
        $item->save();
        return $this->success(__('app.' . $this->translationName . '.updated'), $item);
    }
}
