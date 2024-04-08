<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Enums\ProductGender;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = Product::class;
        $this->translationName = 'product';
    }

    public function getAll(Request $request)
    {
        $user = auth()->user();
        $data = null;
        $data = $this->class::with('product_options');
        if (!$user->isAdmin()) {
            $data = $data->where('store_id', $user->store->id);
        }
        
        $searchQuery = $request->query('search');
        $searchableAttributes = $this->class::SEARCHABLE;
        if ($searchQuery && $searchableAttributes) {
            $data = $data->where(function ($query) use ($searchQuery, $searchableAttributes) {
                foreach ($searchableAttributes as $searchableAttribute) {
                    $query = $query->orWhere($searchableAttribute, 'like', '%' . $searchQuery . '%');
                }
            });
        }

        $filter = $request->query('filter');
        if ($filter) {
            $filter = get_object_vars(json_decode($filter));
            foreach ($filter as $key => $value) {
                if ($value) {
                    $data = $data->where($key, $value);
                }
            }
        }

        $data = $data->paginate(10);
        if (!$data) {
            return $this->failure(__('app.' . $this->translationName . '.model-not-found'), HTTPHeader::NOT_FOUND);
        }

        return $this->success(__('app.' . $this->translationName . '.get-all'), $data);
    }
    
    public function store(ProductRequest $request)
    {
        $user = auth()->user();
        $input = $request->validated();
        $item = new $this->class();
        $item->fill($input);
        $item->price = $input['price'];
        $item->purchase_price = $input['purchase_price'];
        if (!$user->isAdmin() && 
                (!in_array('store_id', $input) || 
                    (in_array('store_id', $input) && $input['store_id'] == null)
                )
        ) {
            $item->store_id = $user->store->id;
        }
        $item->save();
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $item->addMediaFromRequest('image')->toMediaCollection('main');
        }
        if (array_key_exists('product_options', $input)) {
            $productOptions = json_decode($input['product_options']);
            if ($productOptions) {
                foreach (json_decode($input['product_options']) as $productOption) {
                    $productOptionName = $productOption->name;
                    $productOptionModel = new ProductOption();
                    $productOptionModel->name = $productOptionName;
                    $productOptionModel->product_id = $item->id;
                    $productOptionModel->save();
    
                    $productOptionValues = $productOption->product_option_values;
                    foreach ($productOptionValues as $productOptionValue) {
                        $productOptionValueModel = new ProductOptionValue();
                        $productOptionValueModel->value = $productOptionValue->value;
                        $productOptionValueModel->product_option_id = $productOptionModel->id;
                        $productOptionValueModel->save();
                    }
                }
            }
        }
        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    public function update(ProductRequest $request)
    {
        $user = auth()->user();
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $input = $request->validated();
        $item->fill($input);
        $item->price = $input['price'] ?? $item->price;
        $item->purchase_price = $input['purchase_price'] ?? $item->purchase_price;
        if (!$user->isAdmin() && 
                (!in_array('store_id', $input) || 
                    (in_array('store_id', $input) && $input['store_id'] == null)
                )
        ) {
            $item->store_id = $user->store->id;
        }
        $item->save();
        if ($request->hasFile('image')) {
            $mediaItems = $item->getMedia("*");
            foreach ($mediaItems as $mediaItem) {
                $mediaItem->delete();
            }
            $item->addMediaFromRequest('image')->toMediaCollection('main');
        }
        if (array_key_exists('product_options', $input) && $input['product_options']) {
            $productOptions = json_decode($input['product_options']);
            $productOptionsToKeep = [];
            foreach ($productOptions as $productOption) {
                $productOptionName = $productOption->name;
                if (isset($productOption->id)) {    // already exists
                    $productOptionModel = ProductOption::findOrFail($productOption->id);
                    $productOptionModel->name = $productOptionName;
                    $productOptionModel->save();
                    $productOptionsToKeep[] = $productOptionModel->id;

                    $productOptionValuesToKeep = [];
                    foreach ($productOption->product_option_values as $productOptionValue) {
                        if (isset($productOptionValue->id)) {   // already exists
                            $productOptionValueModel = ProductOptionValue::findOrFail($productOptionValue->id);
                            $productOptionValueModel->value = $productOptionValue->value;
                            $productOptionValueModel->save();
                            $productOptionValuesToKeep[] = $productOptionValueModel->id;
                        } else {    // new
                            $productOptionValueModel = new ProductOptionValue();
                            $productOptionValueModel->value = $productOptionValue->value;
                            $productOptionValueModel->product_option_id = $productOptionModel->id;
                            $productOptionValueModel->save();
                            $productOptionValuesToKeep[] = $productOptionValueModel->id;
                        }
                    }
                    if (count($productOptionValuesToKeep)) {
                        ProductOptionValue::where('product_option_id', $productOptionModel->id)
                            ->whereNotIn('id', $productOptionValuesToKeep)
                            ->delete();
                    }
                } else {    // new
                    $productOptionModel = new ProductOption();
                    $productOptionModel->name = $productOptionName;
                    $productOptionModel->product_id = $item->id;
                    $productOptionModel->save();
                    $productOptionsToKeep[] = $productOptionModel->id;

                    foreach ($productOption->product_option_values as $productOptionValue) {
                        $productOptionValueModel = new ProductOptionValue();
                        $productOptionValueModel->value = $productOptionValue->value;
                        $productOptionValueModel->product_option_id = $productOptionModel->id;
                        $productOptionValueModel->save();
                    }
                }
            }
            if (count($productOptionsToKeep)) {
                ProductOption::where('product_id', $item->id)
                    ->whereNotIn('id', $productOptionsToKeep)
                    ->delete();
            }
        } else {
            ProductOption::where('product_id', $item->id)
                ->delete();
        }
        return $this->success(__('app.' . $this->translationName . '.updated'), $item);
    }

    public function getProductVariants()
    {
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        return $this->success(__('app.' . $this->translationName . '.product-variants.get-all'), $item->product_variants);
    }
}
