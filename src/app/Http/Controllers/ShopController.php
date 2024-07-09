<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;


class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::all();
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
