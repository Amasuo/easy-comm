<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = Customer::class;
        $this->translationName = 'customer';
    }

    public function getAll(Request $request)
    {
        $user = auth()->user();
        $data = null;
        $data = $this->class::with('store');
        if (!$user->isAdmin()) {
            $data = $data->where('store_id', $user->store_id);
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
    
    public function store(CustomerRequest $request)
    {
        $user = auth()->user();
        $input = $request->validated();
        $item = new $this->class();
        $item->fill($input);
        if (!$user->isAdmin()) {
            $item->store_id = $user->store_id;
        }
        $item->save();
        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    public function update(CustomerRequest $request)
    {
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $input = $request->validated();
        $item->fill($input);
        $item->save();
        return $this->success(__('app.' . $this->translationName . '.updated'), $item);
    }
}
