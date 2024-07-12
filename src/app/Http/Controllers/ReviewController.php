<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Shop;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;



class ReviewController extends Controller
{
    public function create($shopId)
    {
        $shop = Shop::findOrFail($shopId);
        return view('reviews.create', compact('shop'));
    }

    public function store(ReviewRequest $request)
    {
        // ユーザーが認証されているか確認
        if (!auth()->check()) {
            return redirect()->back()->with('error', '口コミを投稿するにはログインしてください。');
        }

        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('images/temp', 'public');
            $request->session()->put('temp_image_path', $imagePath);
        }
        $userId = Auth::id();

        try {
            Review::create([
                'shop_id' => $request->shop_id,
                'user_id' => $userId,
                'rating' => $request->rating,
                'content' => $request->content,
                'image_path' => $imagePath ? str_replace('temp/', '', $imagePath) : null,
            ]);

            // 成功した場合、一時的な画像を正式な場所に移動
            if ($imagePath) {
                Storage::disk('public')->move($imagePath, str_replace('temp/', '', $imagePath));
                $request->session()->forget('temp_image_path');
            }

            return redirect()->route('shops.detail', ['id' => $request->shop_id])->with('success', '口コミを投稿しました。');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', '口コミの投稿に失敗しました。');
        }
    }

    public function edit($shop_id, $review_id)
    {
        $shop = Shop::findOrFail($shop_id);
        $review = Review::findOrFail($review_id);

        // 現在のユーザーが口コミの作成者かチェック
        if ($review->user_id !== auth()->id()) {
            return redirect()->route('shops.detail', ['id' => $shop_id])->with('error', 'この口コミを編集する権限がありません。');
        }

        return view('reviews.edit', compact('shop', 'review'));
    }

    public function update(ReviewRequest $request, $shop_id, $review_id)
    {
        // ユーザーが認証されているか確認
        if (!auth()->check()) {
            return redirect()->back()->with('error', '口コミを削除するにはログインしてください。');
        }

        $review = Review::findOrFail($review_id);

        // 現在のユーザーが口コミの作成者かチェック
        if ($review->user_id !== auth()->id()) {
            return redirect()->route('shops.detail', ['id' => $shop_id])->with('error', 'この口コミを編集する権限がありません。');
        }

        if ($request->hasFile('image_path')) {
            // Delete old image
            if ($review->image_path) {
                Storage::disk('public')->delete($review->image_path);
            }

            // Store new image
            $imagePath = $request->file('image_path')->store('images', 'public');
            $review->image_path = $imagePath;
        }

        $review->rating = $request->rating;
        $review->content = $request->content;
        $review->save();

        return redirect()->route('shops.detail', ['id' => $shop_id])->with('success', '口コミを編集しました。');
    }

    public function destroy($shop_id, $review_id)
    {
        $review = Review::findOrFail($review_id);

        if ($review->image_path) {
            Storage::disk('public')->delete($review->image_path);
        }
        $review->delete();

        return redirect()->route('shops.detail', ['id' => $shop_id])->with('success', '口コミを削除しました。');
    }

    public function index(Shop $shop)
    {
        $reviews = $shop->reviews()->get(); // この店舗の口コミを取得
        return view('reviews.index', compact('shop','reviews'));
    }
}
