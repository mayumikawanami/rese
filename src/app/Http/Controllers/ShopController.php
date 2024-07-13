<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;


class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Shop::query();

        if ($request->filled('sort')) {
            switch ($request->input('sort')) {
                case 'random':
                    $query->inRandomOrder();
                    break;
                case 'high_rating':
                    $query->leftJoin('reviews', 'shops.id', '=', 'reviews.shop_id')
                    ->select('shops.*')
                    ->selectRaw('AVG(reviews.rating) as avg_rating, COUNT(reviews.id) as review_count')
                    ->orderByDesc('avg_rating')
                    ->orderByRaw('COUNT(reviews.id) = 0') // 評価がない店舗を最後尾に並び替える
                    ->groupBy('shops.id')
                    ->orderBy('shops.id');
                    break;
                case 'low_rating':
                    $query->leftJoin('reviews', 'shops.id', '=', 'reviews.shop_id')
                    ->select('shops.*')
                    ->selectRaw('IFNULL(AVG(reviews.rating), 6) as avg_rating, COUNT(reviews.id) as review_count')
                    ->orderBy('avg_rating')
                    //->orderByRaw('COUNT(reviews.id) = 6') // 評価がない店舗を最後尾に並び替える
                    ->groupBy('shops.id')
                    ->orderBy('shops.id');
                    break;
                default:
                    // Default sorting (you can define your own default behavior)
                    $query->orderBy('id');
                    break;
            }
        } else {
            // Default sorting (you can define your own default behavior)
            $query->orderBy('id');
        }

        $shops = $query->get();
        $areas = Area::distinct()->pluck('name');
        $genres = Genre::distinct()->pluck('name');

        return view('index', compact('shops','areas', 'genres'));
    }

    public function search(Request $request)
    {
        $query = Shop::query();

        if ($request->filled('area')) {
            $query->whereHas('area', function ($query) use ($request) {
                $query->where('name', $request->input('area'));
            });
        }

        if ($request->filled('genre')) {
            $query->whereHas('genre', function ($query) use ($request) {
                $query->where('name', $request->input('genre'));
            });
        }

        if ($request->filled('shop_name')) {
            $query->where('shop_name', 'like', '%' . $request->input('shop_name') . '%');
        }

        $shops = $query->get();
        $areas = Area::distinct()->pluck('name');
        $genres = Genre::distinct()->pluck('name');

        if ($shops->isEmpty()) {
            $message = '見つかりませんでした';
        return view('index', compact('shops', 'areas', 'genres', 'message'));
    }

        return view('index', compact('shops', 'areas', 'genres'));
    }

    public function show($id)
    {
        $shop = Shop::findOrFail($id);

        // 前後の店舗を取得
        $previousShop = Shop::where('id', '<', $shop->id)->orderBy('id', 'desc')->first();
        $nextShop = Shop::where('id', '>', $shop->id)->orderBy('id', 'asc')->first();

        return view('detail', compact('shop', 'previousShop', 'nextShop'));
    }
}
