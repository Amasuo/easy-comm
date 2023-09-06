<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductOptionRequest;
use App\Models\ProductOption;
use Illuminate\Http\Request;

class ProductOptionController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = ProductOption::class;
        $this->translationName = 'product-option';
    }
    
    public function store(ProductOptionRequest $request)
    {
        $input = $request->validated();
        $item = new $this->class();
        $item->fill($input);
        $item->save();
        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    public function update(ProductOptionRequest $request)
    {
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $input = $request->validated();
        $item->fill($input);
        $item->save();
        return $this->success(__('app.' . $this->translationName . '.updated'), $item);
    }
}
