@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reviews/create.css')}}">
@endsection

@section('content')
<div class="review-container">
    <div class="shop-item__content">
        <p class="review-title">今回のご利用はいかがでしたか?</p>
        <div class="review__favorite-shop__item">
            <div class="review__favorite-shop__image">
                <img src="{{ $shop->photo_url }}" alt="{{ $shop->shop_name }}">
            </div>
            <div class="review__favorite-shop__content">
                <h2 class="review__favorite-shop__name">{{ $shop->shop_name }}</h2>
                <div class="review__favorite-shop__tag">
                    <p class="review__favorite-shop__area">#{{ $shop->area->name }}</p>
                    <p class="review__favorite-shop__genre">#{{ $shop->genre->name }}</p>
                </div>
                <div class="review__favorite-shop__details-with-favorite">
                    <a href="{{ route('shops.detail', ['id' => $shop->id]) }}" class="review__favorite-shop__detail-link">詳しくみる</a>
                    <form action="{{ route('favorite.toggle') }}" method="POST">
                        @csrf
                        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                        <button type="submit" class="review__favorite-shop__favorite-button
                                @if(auth()->check() && auth()->user()->favorites->contains($shop->id))
                                    shop-container__favorite-button--red
                                @else
                                    shop-container__favorite-button--gray
                                @endif">
                            <i class="fas fa-heart"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="review-content">
        <div class="rating-form">
            <form action="{{ route('reviews.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                <div class="form-group__rating">
                    <label for="rating" class="form-label__rating">体験を評価してください</label>
                    <div class="star-rating">
                        @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="rating{{ $i }}" name="rating" value="{{ $i }}" class="star-rating__input" {{ old('rating') == $i ? 'checked' : '' }}>
                        <label for="rating{{ $i }}" class="star-rating__label" title="{{ $i }} stars">
                            <i class="fas fa-star"></i>
                        </label>
                        @endfor
                    </div>
                    <p class="error-message">
                        @error('rating')
                        {{ $message }}
                        @enderror
                    </p>
                </div>
                <div class="form-group">
                    <label for="content" class="form-label">口コミを投稿</label>
                    <textarea name="content" id="content" maxlength="400" class="form-control__textarea" placeholder="カジュアルな夜のお出かけにおすすめのスポット" oninput="updateCharCount(this)">{{ old('content') }}</textarea>
                    <div class="char-count">
                        <span id="char-count">{{ old('content') ? mb_strlen(old('content')) : '0' }}</span> / 400
                    </div>
                    <p class="error-message">
                        @error('content')
                        {{ $message }}
                        @enderror
                    </p>
                </div>
                <script>
                    function updateCharCount(element) {
                        var charCount = document.getElementById('char-count');
                        charCount.textContent = element.value.length;
                    }
                </script>

                <div class="form-group">
                    <label for="image_path" class="form-label">画像の追加</label>
                    <div id="drop-zone" class="form-control__image">
                        <div class="click-upload">クリックして写真を追加</div>
                        <div class="drag-upload">またはドラッグアンドドロップ</div>
                        <input type="file" name="image_path" id="image_path" class="form-control__file-input" accept="image/jpeg, image/png">
                        <!-- プレビューエリア -->
                        <div id="preview" class="form-control__preview">
                            @if (session('temp_image_path'))
                            <img src="{{ asset('storage/' . session('temp_image_path')) }}" alt="プレビュー画像">
                            @endif
                        </div>
                    </div>
                    <p class="error-message">
                        @error('image_path')
                        {{ $message }}
                        @enderror
                    </p>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var dropZone = document.getElementById('drop-zone');
                        var fileInput = document.getElementById('image_path');
                        var preview = document.getElementById('preview');

                        // クリックしてファイルを選択
                        dropZone.addEventListener('click', function() {
                            fileInput.click();
                        });

                        // ファイルが選択されたときのイベントハンドラ
                        fileInput.addEventListener('change', function(event) {
                            handleFiles(event.target.files);
                        });

                        // ドラッグオーバーのイベントハンドラ
                        dropZone.addEventListener('dragover', function(event) {
                            event.preventDefault();
                            dropZone.classList.add('dragover');
                        });

                        // ドラッグリーブのイベントハンドラ
                        dropZone.addEventListener('dragleave', function(event) {
                            dropZone.classList.remove('dragover');
                        });

                        // ドロップのイベントハンドラ
                        dropZone.addEventListener('drop', function(event) {
                            event.preventDefault();
                            dropZone.classList.remove('dragover');
                            handleFiles(event.dataTransfer.files);
                            fileInput.files = event.dataTransfer.files;
                        });

                        // ファイルを処理してプレビューを表示する関数
                        function handleFiles(files) {
                            if (files.length === 0) {
                                return;
                            }

                            var file = files[0]; // 1つのファイルのみを処理する

                            if (file.type.startsWith('image/')) {
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    var img = document.createElement('img');
                                    img.src = e.target.result;
                                    img.className = 'preview-image'; // クラス名を追加
                                    preview.innerHTML = ''; // 既存のプレビューをクリア
                                    preview.appendChild(img);
                                };
                                reader.readAsDataURL(file);
                            } else {
                                alert('JPEGまたはPNG形式の画像ファイルを選択してください。');
                            }
                        }
                    });
                </script>
                <button type="submit" class="submit-button">口コミを投稿</button>
            </form>
        </div>
    </div>
</div>
@endsection