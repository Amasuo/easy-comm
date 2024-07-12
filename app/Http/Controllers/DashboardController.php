<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Helpers\AuthHelper;
use App\Helpers\DashboardHelper;
use App\Helpers\GeneralHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\RegisterAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function getAll(Request $request)
    {
        $user = auth()->user();

        $res = new \stdClass();
        $res->is_admin = false;
        $res->is_store_admin = false;
        if($user->isAdmin()) {
            $res->is_admin = true;
        } elseif($user->isStoreAdmin()) {
            $res->is_store_admin = true;
            $res->number_customers = DashboardHelper::getNumberOfCustomersForStoreId($user->store->id);
            $res->number_product_variants = DashboardHelper::getNumberOfProductVariantsForStoreId($user->store->id);
            $res->number_orders = DashboardHelper::getNumberOfOrdersForStoreId($user->store->id);
            $res->monthly_growth = DashboardHelper::getMonthlyGrowthForStoreId($user->store->id);
            $res->top_products = DashboardHelper::getTopProductsForStoreId($user->store->id);
            $res->weekly_sales = DashboardHelper::getWeeklSalesForStoreId($user->store->id);
            $res->weekly_costs = DashboardHelper::getWeeklyCostsForStoreId($user->store->id);
            $res->weekly_profit = DashboardHelper::getWeeklyProfitForStoreId($user->store->id);
            $res->total_sales = DashboardHelper::getTotalSalesForStoreId($user->store->id);
            $res->total_costs = DashboardHelper::getTotalCostsForStoreId($user->store->id);
            $res->total_profit = DashboardHelper::getTotalProfitForStoreId($user->store->id);
            $res->test = DashboardHelper::test($user->store->id);
        }
        return $res;
    }
}
