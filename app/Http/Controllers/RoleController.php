<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
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
}
