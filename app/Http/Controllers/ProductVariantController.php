<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductVariant\ProductVariantRequest;
use App\Http\Requests\ProductVariant\StoreProductOptionValuesRequest;
use App\Models\ProductOptionValue;
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

    public function getAll(Request $request)
    {
        $user = auth()->user();
        $data = $this->class::query();
        if (!$user->isAdmin()) {
            $data = $data->whereHas('product', function ($query) use ($user) {
                $query->where('store_id', $user->store->id);
            });
        }
        $searchQuery = $request->query('search');
        if ($searchQuery && $this->class::SEARCHABLE) {
            foreach ($this->class::SEARCHABLE as $searchableAttribute) {
                $data = $data->orWhere($searchableAttribute, 'like', '%' . $searchQuery . '%');
            }
        }

        $filter = $request->query('filter');
        if ($filter) {
            $filter = get_object_vars(json_decode($filter));
            foreach ($filter as $key => $value) {
                if ($value) {
                    if($key === 'store_id') {
                        $data = $data->whereHas('product', function ($query) use ($key, $value) {
                            $query->where($key, $value);
                        });
                    } else {
                        $data = $data->where($key, $value);
                    }
                }
            }
        }

        $data = $data->paginate(10);
        if (!$data) {
            return $this->failure(__('app.' . $this->translationName . '.model-not-found'), HTTPHeader::NOT_FOUND);
        }

        return $this->success(__('app.' . $this->translationName . '.get-all'), $data);
    }
    
    public function store(ProductVariantRequest $request)
    {
        $input = $request->validated();
        $item = new $this->class();
        $item->fill($input);
        $item->price = $input['price'] ?? null;
        $item->purchase_price = $input['purchase_price'] ?? null;
        $item->save();
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $item->addMediaFromRequest('image')->toMediaCollection('main');
        }

        $productOptionValues = json_decode($input['product_option_values']);
        if ($productOptionValues) {
            $item->product_option_values()->attach($productOptionValues);
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
        $item->purchase_price = $input['purchase_price'] ?? null;
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
