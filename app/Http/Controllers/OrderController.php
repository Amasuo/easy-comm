<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Http\Requests\OrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = Order::class;
        $this->translationName = 'order';
    }

    public function getAll(Request $request)
    {
        $user = auth()->user();
        $data = null;
        $data = $this->class::query();
        if (!$user->isAdmin()) {
            $data = $data->where('store_id', $user->store_id);
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
    
    public function store(OrderRequest $request)
    {
        $input = $request->validated();

        // check stock of product variants passed first (example of product variant passed : {"id" => 1, "count" => 4})
        $productVariantItems = $input['product_variants'];
        foreach ($productVariantItems as $productVariantItem) {
            $productVariant = ProductVariant::findOrFail($productVariantItem['id']);
            if ($productVariant->stock < $productVariantItem['count']) {
                return $this->failure('app.' . $this->translationName . '.product-variant.insufficient-stock');
            }
        }

        $item = new $this->class();
        $item->fill($input);

        // find or create customer
        $customerId = $input['customer_id'] ?? null;
        $customer = Customer::find($customerId);
        if (!$customer) {
            $customer = Customer::where('store_id', $input['store_id'])
            ->where('firstname', $input['firstname'])
            ->where('lastname', $input['lastname'])
            ->where('phone', $input['phone'])
            ->first();

            if ($customer) {
                // update address
                $customer->state = $input['state'];
                $customer->city = $input['city'];
                $customer->street = $input['street'];
            } else {
                // create new customer
                $customer = new Customer();
                $customer->fill($input);
            }
            $customer->save();
        }

        $item->customer_id = $customer->id;
        $item->save();

        $syncData = [];
        // decrease stock
        foreach ($productVariantItems as $productVariantItem) {
            $productVariant = ProductVariant::findOrFail($productVariantItem['id']);
            $productVariant->stock -= $productVariantItem['count'];
            $productVariant->save();

            $syncData[$productVariant->id] = [
                'count' => $productVariantItem['count'],
            ];
        }

        $item->product_variants()->sync($syncData);

        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    // @todo : enable updating product variants of an order
    public function update(OrderRequest $request)
    {
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $input = $request->validated();
        $item->fill($input);
        $item->save();

        $customer = $item->customer;
        $customer->fill($input);
        $customer->save();

        return $this->success(__('app.' . $this->translationName . '.updated'), $item);
    }
}
