<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Auth;

class BelongingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $modelType): Response
    {
        $user = Auth::user();

        // Determine the model class based on the provided $modelType
        $modelClass = 'App\\Models\\' . ucfirst($modelType);

        // Check if the user owns the requested model
        $model = $modelClass::find($request->id);

        if (!$model || $model->user_id !== $user->id) {
            // Unauthorized access, return an error or redirect
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->requiredModel = $model;

        return $next($request);
    }
}
