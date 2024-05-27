<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;


class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::all();
        $areas = Shop::distinct()->pluck('area');
        $genres = Shop::distinct()->pluck('genre');

        return view('index', compact('shops','areas', 'genres'));
    }

    public function search(Request $request)
    {
        $query = Shop::query();

        if ($request->filled('area')) {
            $query->where('area', $request->input('area'));
        }

        if ($request->filled('genre')) {
            $query->where('genre', $request->input('genre'));
        }

        if ($request->filled('shop_name')) {
            $query->where('shop_name', 'like', '%' . $request->input('shop_name') . '%');
        }

        $shops = $query->get();
        $areas = Shop::distinct()->pluck('area');
        $genres = Shop::distinct()->pluck('genre');

        if ($shops->isEmpty()) {
            $message = '見つかりませんでした';
        return view('index', compact('shops', 'areas', 'genres', 'message'));
    }

        return view('index', compact('shops', 'areas', 'genres'));
    }

    public function show($id)
    {
        $shop = Shop::find($id);
        return view('detail', compact('shop'));
    }
}
