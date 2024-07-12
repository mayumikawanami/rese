<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class CheckReviewOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $review = Review::findOrFail($request->route('review_id'));

        if ($review->user_id !== Auth::id()) {
            return redirect()->route('shops.detail', ['id' => $request->route('shop_id')])->with('error', '権限がありません。');
        }

        return $next($request);
    }
}
