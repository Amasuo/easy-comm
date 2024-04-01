<?php

namespace App\Http\Middleware\Permissions;

use App\Enums\HTTPHeader;
use App\Models\Store;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAndStoreRelatedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return $next($request);
        }
        $requestedStoreId = $request->id;
        $relatedStoreIds = $user->getRelatedStoresQuery()->pluck('id')->toArray();
        if (!in_array($requestedStoreId, $relatedStoreIds)) {
            abort(HTTPHeader::FORBIDDEN, __('unauthorized'));
        }
        return $next($request);
    }
}
