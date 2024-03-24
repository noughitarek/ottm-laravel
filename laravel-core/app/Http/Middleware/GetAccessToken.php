<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class GetAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Schema::hasTable('access_tokens'))
        {
            $access_token = AccessToken::where("expired_at", null)->first();
            if (!$access_token) {
                return AccessToken::redirectToFacebook();
            }else{
                config(['settings.access_token' => $access_token]);
            }
        }
        return $next($request);

    }
}
