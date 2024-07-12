@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reviews/index.css')}}">
@endsection

@section('content')
<div class="all-reviews-page">
    <h2>{{ $shop->shop_name }} の全ての口コミ情報</h2>

    @if ($reviews->count() > 0)
    @foreach ($reviews as $review)
    <div class="review">
        @if (auth()->check() && auth()->user()->id == $review->user_id)
        <!-- 編集ボタン -->
        <a href="{{ route('reviews.edit', ['shop_id' => $shop->id, 'review_id' => $review->id]) }}" class="edit-button">口コミを編集</a>
        @endif
        @if (auth()->check() && (auth()->user()->id == $review->user_id || auth()->user()->hasRole('admin')))
        <!-- 削除ボタン -->
        <form action="{{ route('reviews.destroy', ['shop_id' => $shop->id, 'review_id' => $review->id]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete-button">口コミを削除</button>
        </form>
        @endif
        <div class="detail__star-rating">
            @for ($i = 1; $i <= 5; $i++) @if ($i <=$review->rating)
                <i class="fas fa-star"></i>
                @else
                <i class="far fa-star"></i>
                @endif
                @endfor
        </div>
        <div class="detail__review-content">{{ $review->content }}</div>
        @if ($review->image_path)
        <img class="review-img" src="{{ asset('storage/' . $review->image_path) }}" alt="Review Image" style="max-width: 100px;">
        @endif
    </div>
    @endforeach
    @else
    <p class="review-message">この店舗には口コミはありません</p>
    @endif
    <button onclick="window.location='{{ url()->previous() }}'" class="back-button">戻る</button>
</div>
@endsection