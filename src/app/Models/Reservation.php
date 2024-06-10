<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Mail\ReservationReminder;
use Illuminate\Support\Facades\Mail;

class Reservation extends Model
{
    use HasFactory;

    // テーブル名を指定（デフォルトは 'reservations'）
    protected $table = 'reservations';

    // 主キーを指定（デフォルトは 'id'）
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'shop_id',
        'reservation_date',
        'reservation_time',
        'number',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // 予約のリマインダーメールを送信するメソッド
    public static function sendDailyReminders()
    {
        // 今日の予約を取得
        $reservations = self::whereDate('reservation_date', '=', now()->toDateString())->get();

        foreach ($reservations as $reservation) {
            // リマインダーメールを送信
            Mail::to($reservation->user->email)->send(new ReservationReminder($reservation));
        }

    }
}
