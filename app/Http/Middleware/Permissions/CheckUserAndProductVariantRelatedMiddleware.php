<?php

namespace App\Http\Middleware\Permissions;

use App\Enums\HTTPHeader;
use App\Models\ProductVariant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAndProductVariantRelatedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $requestedProductVariantId = $request->id;
        $productVariant = ProductVariant::find($requestedProductVariantId);
        if (!$productVariant) {
            abort(HTTPHeader::NOT_FOUND, __('app.product-variant.model-not-found'));
        }
        $product = $productVariant->product;
        if (!$product) {
            abort(HTTPHeader::NOT_FOUND, __('app.product-variant.product.model-not-found'));
        }
        if (!$user->isAdmin() && $product->store_id != $user->store_id) {
            abort(HTTPHeader::FORBIDDEN, __('unauthorized'));
        }
        return $next($request);
    }
}
