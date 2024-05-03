<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\FacebookPage;
use App\Models\FacebookMessage;
use Illuminate\Console\Command;
use App\Models\DashboardResponseTime;

class UpdateResponseTimeDashboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-response-time-dashboard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if(!config('settings.scheduler.update_response_time'))
            exit;
        
        $messagesPerDayMinutes = FacebookMessage::selectRaw('DATE(created_at) as date, MINUTE(created_at) AS message_minute, HOUR(created_at) AS message_hour, GROUP_CONCAT(id) as ids_list')
        ->where('created_at', '>=', Carbon::now()->subMonths(2))
        ->where('sented_from', 'user')
        ->groupBy('date', 'message_hour', 'message_minute')
        ->orderBy('date', 'desc')
        ->orderBy('message_hour', 'asc')
        ->orderBy('message_minute', 'asc')
        ->get();
        foreach ($messagesPerDayMinutes as $messages) {
            $ids_array = explode(',', $messages->ids_list);
            $minute = sprintf("%02d", $messages->message_minute);
            $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $messages->date . ' ' . $messages->message_hour . ':' . $minute . ':00');
            unset($total_response_time);
            unset($message_count);
            unset($average_response_time);
            foreach($ids_array as $id) {
                $message = FacebookMessage::find($id);
                if ($message)
                {
                    $page = FacebookPage::where('facebook_page_id', function($query) use ($message) {
                        $query->select('page')
                              ->from('facebook_conversations')
                              ->where('facebook_conversation_id', $message->conversation)
                              ->first();
                    })->first()->id;
                    if(isset($total_response_time[$page]))
                    {
                        $total_response_time[$page] += $message->Response_Time();
                    }
                    else
                    {
                        $total_response_time[$page] = $message->Response_Time();
                    }
                    if(isset($message_count[$page]))
                    {
                        $message_count[$page]++;
                    }
                    else
                    {
                        $message_count[$page] = 1;
                    }
                }
            }
            foreach($message_count as $page=>$message_cnt)
            {
                if ($message_cnt > 0) {
                    $average_response_time[$page] = $total_response_time[$page] / $message_count[$page];
                    
                } else {
                    $average_response_time[$page] = 0;
                }
            }
            $results = DashboardResponseTime::where('minute', $datetime)->where('page', $page)->first();
            if(!$results)
            {
                DashboardResponseTime::create([
                    'minute' => $datetime,
                    'page' => $page,
                    'value' => $average_response_time[$page],
                ]);
            }
            else
            {
                $results->update([
                    'minute' => $datetime,
                    'page' => $page,
                    'value' => $average_response_time[$page],
                ]);
            }
        }
    }
}
