<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;



class Authenticate extends BaseMiddleware
{
    
    //  * Get the path the user should be redirected to when they are not authenticated.
    //  *
    //  *@param  \Illuminate\Http\Request  $request
    //   @return string|null
     


    // protected function redirectTo($request)
    // { 
    //     if (! $request->expectsJson()) {
    //         return route('login');
    //     }
    // }

    public function handle( $request, Closure $next)
    {
    try {
            
            JWTAuth::parseToken()->authenticate();
            return $next($request);
        } catch (TokenBlacklistedException $e) {
            return response()->json(['error' => 'Token is blacklisted.'], 401);
        } 
        catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token is expired.'], 401);
        }catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token is invalid.'], 401);
        }catch (\Exception $e){
            return response()->json(['error' => 'Authorization Token not found.'], 401);
        }
        // if(!JWTAuth::parseToken()->authenticate()) {
        //     return response()->json([
        //         'message' => 'Unauthorized'
        //     ], 401);
        // }

    }
}
