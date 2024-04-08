<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Http\Requests\StoreRequest;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = Role::class;
        $this->translationName = 'role';
    }

    public function getAll(Request $request)
    {
        $user = auth()->user();
        $data = $this->class::query();

        // if not admin hide admin role
        if (!$user->isAdmin()) {
            $data->whereNotIn('id', [1]);
        }

        $searchQuery = $request->query('search');
        if ($searchQuery && $this->class::SEARCHABLE) {
            foreach ($this->class::SEARCHABLE as $searchableAttribute) {
                $data = $data->orWhere($searchableAttribute, 'like', '%' . $searchQuery . '%');
            }
        }
        $data = $data->paginate(10);
        if (!$data) {
            return $this->failure(__('app.' . $this->translationName . '.model-not-found'), HTTPHeader::NOT_FOUND);
        }

        return $this->success(__('app.' . $this->translationName . '.get-all'), $data);
    }

    public function store(StoreRequest $request)
    {
        $input = $request->validated();
        $item = new $this->class();
        $item->fill($input);
        $item->save();

        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    public function update(StoreRequest $request)
    {
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $input = $request->validated();
        if (array_key_exists('name', $input)) {
            if (GeneralHelper::valueTakenForClassAttribute($this->class, 'name', $input['name'], $this->modelId)) {
                return $this->failure(__('app.' . $this->translationName . '.name-taken'));
            }
        }
        $item->fill($input);
        $item->save();
        return $this->success(__('app.' . $this->translationName . '.updated'), $item);
    }
}
