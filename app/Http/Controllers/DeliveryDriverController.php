<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeliveryDriverRequest;
use App\Models\DeliveryDriver;
use Illuminate\Http\Request;

class DeliveryDriverController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = DeliveryDriver::class;
        $this->translationName = 'delivery-driver';
    }

    public function getAll(Request $request)
    {
        $user = auth()->user();
        $data = null;
        if ($user->isAdmin()) {
            $data = $this->class::query();
        } else {
            $data = $this->class::where('store_id', $user->store->id);
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
    
    public function store(DeliveryDriverRequest $request)
    {
        $user = auth()->user();
        $input = $request->validated();
        $item = new $this->class();
        $item->fill($input);
        if (!$user->isAdmin()) {
            $item->store_id = $user->store->id;
        }
        $item->save();
        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    public function update(DeliveryDriverRequest $request)
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
        return $this->success(__('app.' . $this->translationName . '.updated'), $item);
    }
}
