<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckToDoTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CheckToDoTime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '檢查ToDo項目的執行時間，如果已經到設定就發送通知';

    /**
     * 距離現在多久檢查時間(分鐘)
     */
    const CHECK_TIME_MINS = 10;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 現在時間X分鐘前
        $timestamp_ago = Carbon::now()->addMinutes(-SELF::CHECK_TIME_MINS);

        $events = Event::whereBetween('time', [$timestamp_ago, Carbon::now()])->get();
        foreach ($events as $event) {
            $this->info('您的項目 : ' . $event->title . " ，時間就快到了");
        }
    }
}
