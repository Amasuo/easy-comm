<?php

namespace App\Http\Middleware\Permissions;

use App\Enums\HTTPHeader;
use App\Models\ProductOptionValue;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAndProductOptionValueRelatedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $requestedProductOptionValueId = $request->id;
        $productOptionValue = ProductOptionValue::find($requestedProductOptionValueId);
        if (!$productOptionValue) {
            abort(HTTPHeader::NOT_FOUND, __('app.product-option-value.model-not-found'));
        }
        $productOption = $productOptionValue->product_option;
        if (!$productOption) {
            abort(HTTPHeader::NOT_FOUND, __('app.product-option-value.product-option.model-not-found'));
        }
        $product = $productOption->product;
        if (!$product) {
            abort(HTTPHeader::NOT_FOUND, __('app.product-option-value.product-option.product.model-not-found'));
        }
        if (!$user->isAdmin() && $product->store_id != $user->store_id) {
            abort(HTTPHeader::FORBIDDEN, __('unauthorized'));
        }
        return $next($request);
    }
}
