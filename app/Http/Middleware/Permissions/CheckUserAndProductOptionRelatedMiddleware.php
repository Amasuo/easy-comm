<?php

namespace App\Http\Middleware\Permissions;

use App\Enums\HTTPHeader;
use App\Models\ProductOption;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAndProductOptionRelatedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $requestedProductOptionId = $request->id;
        $productOption = ProductOption::find($requestedProductOptionId);
        if (!$productOption) {
            abort(HTTPHeader::NOT_FOUND, __('app.product-option.model-not-found'));
        }
        $product = $productOption->product;
        if (!$product) {
            abort(HTTPHeader::NOT_FOUND, __('app.product-option.product.model-not-found'));
        }
        if (!$user->isAdmin() && $product->store_id != $user->store->id) {
            abort(HTTPHeader::FORBIDDEN, __('unauthorized'));
        }
        return $next($request);
    }
}
