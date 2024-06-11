<!DOCTYPE html>
<html>

<head>
    <title>Reservation Reminder</title>
</head>

<body>
    <h1>予約リマインダー</h1>
    <p>こんにちは、{{ $reservation->user->name }}さん。</p>
    <p>今日の予約情報をお知らせします。</p>
    <ul>
        <li>ショップ名: {{ $reservation->shop->shop_name }}</li>
        <li>予約日: {{ $reservation->reservation_date }}</li>
        <li>予約時間: {{ $reservation->reservation_time }}</li>
        <li>人数: {{ $reservation->number }}人</li>
    </ul>
</body>

</html>