<?php

namespace App\Http\Controllers;

use App;
use App\Enums\HTTPHeader;
use App\Http\Requests\OrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelPdf\Facades\Pdf;

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
        $data = $this->class::query();
        if (!$user->isAdmin()) {
            $data = $data->where('store_id', $user->store->id);
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

        $startDate = $request->query('startDate');
        $endDate = $request->query(key: 'endDate');
        if($startDate && $endDate && $startDate == $endDate) {
            $data = $data->whereDate('created_at', $startDate);
        } else {
            if($startDate) {
                $data = $data->where('created_at', '>=', $startDate);
            }
            if($endDate) {
                $data = $data->where('created_at', '<=', $endDate);
            }
        }
        
        $data = $data->orderBy('created_at', 'desc')->paginate(10);
        if (!$data) {
            return $this->failure(__('app.' . $this->translationName . '.model-not-found'), HTTPHeader::NOT_FOUND);
        }

        return $this->success(__('app.' . $this->translationName . '.get-all'), $data);
    }
    
    public function store(OrderRequest $request)
    {
        $user = auth()->user();
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
        $item->order_status_id = 1;

        if (!$user->isAdmin()) {
            $item->store_id = $user->store->id;
        }

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
        $user = auth()->user();
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $input = $request->validated();
        $item->fill($input);
        if (!$user->isAdmin()) {
            $item->store_id = $user->store->id;
        }
        $item->save();

        $customer = $item->customer;
        $customer->fill($input);
        $customer->save();

        return $this->success(__('app.' . $this->translationName . '.updated'), $item);
    }
}
