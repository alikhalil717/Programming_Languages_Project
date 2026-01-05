<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FlutterCompatibleResponse
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse && str_contains($request->path(), 'api/')) {
            $data = $response->getData(true);

            if (is_array($data)) {
                if (!isset($data['success'])) {
                    $data['success'] = $response->getStatusCode() >= 200 && $response->getStatusCode() < 300;
                }

                if (!isset($data['message']) && !$data['success']) {
                    $data['message'] = 'حدث خطأ';
                }

                $response->setData($data);
                $response->setEncodingOptions(
                    JSON_UNESCAPED_UNICODE |
                    JSON_UNESCAPED_SLASHES
                );
            }
        }

        return $response;
    }
}