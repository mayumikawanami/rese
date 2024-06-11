<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmed</title>
</head>

<body>
    <h1>予約が確定されました</h1>
    <p>{{ $user->name }} 様,</p>
    <p>以下の予約が確定されました：</p>
    <ul>
        <li>店舗名: {{ $reservation->shop->shop_name }}</li>
        <li>予約日: {{ $reservation->reservation_date }}</li>
        <li>予約時間: {{ $reservation->reservation_time }}</li>
        <li>人数: {{ $reservation->number }}人</li>
    </ul>
    <p>ご来店をお待ちしております。</p>
</body>

</html>