<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Helpers\GeneralHelper;
use App\Http\Requests\DeliveryCompanyRequest;
use App\Models\DeliveryCompany;
use Illuminate\Http\Request;

class DeliveryCompanyController extends Controller
{
    public function getAll(Request $request)
    {
        $deliveryCompanies = DeliveryCompany::all();
        if (!$deliveryCompanies) {
            return $this->failure(__('app.delivery-company.model-not-found'), HTTPHeader::NOT_FOUND);
        }

        return $this->success(__('app.delivery-company.get-all'), $deliveryCompanies);
    }

    public function getItem(Request $request)
    {
        $this->validateId();
        $deliveryCompany = DeliveryCompany::find($this->model_id);
        if (!$deliveryCompany) {
            return $this->failure(__('app.delivery-company.model-not-found'), HTTPHeader::NOT_FOUND);
        }

        return $this->success(__('app.delivery-company.get-one'), $deliveryCompany);
    }
    
    public function store(DeliveryCompanyRequest $request)
    {
        $input = $request->validated();
        $deliveryCompany = new DeliveryCompany();
        $deliveryCompany->fill($input);
        $deliveryCompany->save();
        return $this->success(__('app.delivery-company.created'), $deliveryCompany);
    }

    public function update(DeliveryCompanyRequest $request)
    {
        $this->validateId();
        $deliveryCompany = DeliveryCompany::findOrFail($this->model_id);
        $input = $request->validated();
        if (array_key_exists('name', $input)) {
            if (GeneralHelper::valueTakenForClassAttribute(DeliveryCompany::class, 'name', $input['name'], $this->model_id)) {
                return $this->failure(__('app.delivery-company.name-taken'));
            }
        }
        if (array_key_exists('phone', $input)) {
            if (GeneralHelper::valueTakenForClassAttribute(DeliveryCompany::class, 'phone', $input['phone'], $this->model_id)) {
                return $this->failure(__('app.delivery-company.phone-taken'));
            }
        }
        $deliveryCompany->fill($input);
        $deliveryCompany->save();
        return $this->success(__('app.delivery-company.updated'), $deliveryCompany);
    }

    public function delete(Request $request)
    {
        $this->validateId();
        $store = DeliveryCompany::findOrFail($this->model_id);
        $store->delete();
        return $this->success(__('app.delivery-company.deleted'), $store);
    }
}
