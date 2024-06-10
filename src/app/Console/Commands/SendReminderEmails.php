<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Mail\ReservationReminder;
use Illuminate\Support\Facades\Mail;

class SendReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for upcoming reservations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 予約日が今日の予約を取得
        $today = now()->startOfDay();
        $reservations = Reservation::whereDate('reservation_date', $today)->get();

        // 各予約にリマインダーメールを送信
        foreach ($reservations as $reservation) {
            Mail::to($reservation->user->email)->send(new ReservationReminder($reservation));
        }

        $this->info('Reminder emails sent successfully.');
    }
}
