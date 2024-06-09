@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shopManager/scan.css')}}">
@endsection

@section('content')
<div class="scan-container">
    <h1 class="scan-container__title">QRコードスキャン</h1>

    <!-- カメラプレビュー領域 -->
    <div id="qr-reader" style="width:500px"></div>
    <div id="qr-reader-results"></div>

    <!-- QRコードデータ送信用フォーム -->
    <form id="qr-code-form" method="POST" action="{{ route('shopManager.scan.qrcode') }}" style="display: none;">
        @csrf
        <input type="hidden" name="qr_code_data" id="qr_code_data">
        <button type="submit" class="btn btn-primary">QRコードを送信</button>
    </form>
</div>

<script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        // Handle on success condition with the decoded text or result.
        console.log(`Scan result: ${decodedText}`);
        document.getElementById('qr_code_data').value = decodedText;
        console.log(`QR Code Data: ${document.getElementById('qr_code_data').value}`);
        document.getElementById('qr-code-form').submit();
    }

    function onScanFailure(error) {
        // Handle scan failure, usually better to ignore and keep scanning.
        console.warn(`QR error = ${error}`);
    }

    let html5QrCode = new Html5Qrcode("qr-reader");
    let qrboxSize = 250;
    let config = {
        fps: 10,
        qrbox: {
            width: qrboxSize,
            height: qrboxSize
        }
    };

    html5QrCode.start({
            facingMode: "environment"
        }, config, onScanSuccess, onScanFailure)
        .catch(err => {
            console.error(`Unable to start scanning, error: ${err}`);
        });
    // Start scanning.
    //html5QrCode.start({
    //facingMode: "environment"
    //}, // Alternatively use { facingMode: "user" }
    //config,
    //onScanSuccess,
    //onScanFailure
    //);
</script>
<div class="scan-container__button">
    <a class="back-button" href="{{ route('shopManager.dashboard') }}">戻る</a>
</div>

@endsection