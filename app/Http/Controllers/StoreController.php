<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Helpers\GeneralHelper;
use App\Http\Requests\StoreRequest;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = Store::class;
        $this->translationName = 'store';
    }

    public function getAll(Request $request)
    {
        $user = auth()->user();
        $data = $this->class::query();

        // if admin return only parent stores
        if ($user->isAdmin()) {
            $withChildren = $request->query('with-children');
            if (!$withChildren) {
                $data = $data->whereNull('parent_id');
            }
        } else { // store admin -> return only related stores
            $parentStore = $user->store;
            $storeIds = $parentStore->children->pluck('id')->toArray();
            array_push($storeIds, $parentStore->id);
            $data = $data->whereIn('id', $storeIds);
        }

        $searchQuery = $request->query('search');
        if ($searchQuery && $this->class::SEARCHABLE) {
            foreach ($this->class::SEARCHABLE as $searchableAttribute) {
                $data = $data->orWhere($searchableAttribute, 'like', '%' . $searchQuery . '%');
                // if admin return only parent stores
                if ($user->isAdmin()) {
                    $data->whereNull('parent_id');
                } else { // store admin -> return only related stores
                    $parentStore = $user->store;
                    $storeIds = $parentStore->children->pluck('id')->toArray();
                    array_push($storeIds, $parentStore->id);
                    $data = $data->whereIn('id', $storeIds);
                }
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
        $user = auth()->user();

        $input = $request->validated();
        $item = new $this->class();
        $item->fill($input);
        $item->save();
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $item->addMediaFromRequest('image')->toMediaCollection('main');
        }

        if ($user->isStoreAdmin()) {
            $item->addUser($user, isAdmin: true);
        }

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
        if ($request->hasFile('image')) {
            $mediaItems = $item->getMedia("*");
            foreach ($mediaItems as $mediaItem) {
                $mediaItem->delete();
            }
            $item->addMediaFromRequest('image')->toMediaCollection('main');
        }
        return $this->success(__('app.' . $this->translationName . '.updated'), $item);
    }
}
