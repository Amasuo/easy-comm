<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Enums\RoleName;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, ApiResponse;

    protected $modelId = null;
    protected $class = null;
    protected $translationName = null;
    public function __construct(Request $request)
    {
        if (isset($request->id)) {
            $this->modelId = $request->id;
        }
    }

    protected function validateId()
    {
        if (is_null($this->modelId)) {
            $this->abort(__('app.generic.id-not-found'), HTTPHeader::NOT_FOUND);
        }
    }

    public function getAll(Request $request)
    {
        $data = $this->class::query();
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

    public function getItem(Request $request)
    {
        $this->validateId();
        $item =  $this->class::find($this->modelId);
        if (!$item) {
            return $this->failure(__('app.' . $this->translationName . '.model-not-found'), HTTPHeader::NOT_FOUND);
        }

        return $this->success(__('app.' . $this->translationName . '.get-one'), $item);
    }

    public function delete(Request $request)
    {
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $item->delete();
        return $this->success(__('app.' . $this->translationName . '.deleted'), $item);
    }

    public function getRoleNames(Request $request) {
        $user = auth()->user();
        $data = RoleName::toArray();
        if (!$user->isAdmin()) {
            $data = array_values(array_diff($data, [RoleName::ADMIN]));
        }
        return $this->success(__('app.role.get-all'), $data);
    }
}
