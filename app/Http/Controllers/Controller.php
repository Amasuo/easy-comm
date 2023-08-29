<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, ApiResponse;

    protected $model_id = null;

    public function __construct(Request $request)
    {
        if (isset($request->id)) {
            $this->model_id = $request->id;
        }
    }

    protected function validateId()
    {
        if (is_null($this->model_id)) {
            $this->abort(__('app.generic.id-not-found'), HTTPHeader::NOT_FOUND);
        }
    }
}
