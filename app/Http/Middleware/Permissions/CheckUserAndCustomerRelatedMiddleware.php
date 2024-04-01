<?php

namespace App\Http\Middleware\Permissions;

use App\Enums\HTTPHeader;
use App\Models\Customer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAndCustomerRelatedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $requestedCustomerId = $request->id;
        $customer = Customer::find($requestedCustomerId);
        if (!$customer) {
            abort(HTTPHeader::NOT_FOUND, __('app.customer.model-not-found'));
        }
        if (!$user->isAdmin() && !in_array($user->getRelatedStoresQuery()->pluck('id')->toArray(), $customer->store_id)) {
            abort(HTTPHeader::FORBIDDEN, __('unauthorized'));
        }
        return $next($request);
    }
}
