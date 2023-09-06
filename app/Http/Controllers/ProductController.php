<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = Product::class;
        $this->translationName = 'product';
    }
    
    public function store(ProductRequest $request)
    {
        $input = $request->validated();
        $item = new $this->class();
        $item->fill($input);
        $item->price = $request['price'];
        $item->save();
        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    public function update(ProductRequest $request)
    {
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $input = $request->validated();
        $item->fill($input);
        $item->price = $request['price'] ?? $item->price;
        $item->save();
        return $this->success(__('app.' . $this->translationName . '.updated'), $item);
    }

    public function getProductVariants()
    {
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        return $this->success(__('app.' . $this->translationName . '.product-variants.get-all'), $item->product_variants);
    }
}
