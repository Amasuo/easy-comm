<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductVariant\ProductVariantRequest;
use App\Http\Requests\ProductVariant\StoreProductOptionValuesRequest;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $item->price = $input['price'] ?? null;
        $item->save();
        Log::debug('aaa');
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            Log::debug('bbb');
            $item->addMediaFromRequest('image')->toMediaCollection('main');
        }
        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    public function update(ProductVariantRequest $request)
    {
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $input = $request->validated();
        $item->fill($input);
        $item->price = $input['price'] ?? null;
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

    public function storeProductOptionValues(StoreProductOptionValuesRequest $request)
    {
        $this->validateId();
        $input = $request->validated();
        $item = $this->class::findOrFail($this->modelId);
        $ids = $input['ids'];
        $item->product_option_values()->sync($ids);
        $item->refresh();
        return $this->success(__('app.' . $this->translationName . '.product-options-values.stored'), $item);
    }
}
