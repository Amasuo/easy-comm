<?php

namespace App\Http\Middleware\Permissions;

use App\Enums\HTTPHeader;
use App\Models\Order;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAndOrderRelatedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $requestedOrderId = $request->id;
        $order = Order::find($requestedOrderId);
        if (!$order) {
            abort(HTTPHeader::NOT_FOUND, __('app.order.model-not-found'));
        }
        if (!$user->isAdmin() && !in_array($order->store_id, $user->getRelatedStoresQuery()->pluck('id')->toArray())) {
            abort(HTTPHeader::FORBIDDEN, __('unauthorized'));
        }
        return $next($request);
    }
}
