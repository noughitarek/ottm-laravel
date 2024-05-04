<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Remarketing;
use App\Models\FacebookPage;
use Illuminate\Http\Request;
use App\Models\FacebookMessage;
use Illuminate\Support\Facades\DB;
use App\Models\RemarketingMessages;
use App\Models\DashboardResponseTime;
use App\Models\RemarketingIntervalMessages;

class DashboardController extends Controller
{
    
    public function index()
    {
        /*$pages = FacebookPage::where('type', 'business')->whereNull('expired_at')->get();
        return view('pages.dashboard.index')->with('ResponseTime', DashboardResponseTime::class)->with('pages', $pages);*/
        $messagesPerDayHours = FacebookMessage::selectRaw('DATE(created_at) as date, HOUR(created_at) AS message_hour, GROUP_CONCAT(id) as ids_list')
        ->where('created_at', '>=', Carbon::now()->subDays(2))
        ->where('sented_from', 'user')
        ->groupBy('date', 'message_hour')
        ->orderBy('date', 'desc')
        ->orderBy('message_hour', 'asc')
        ->get();

        $data = array();
        $remarketingMessages = RemarketingMessages::selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->groupBy('date')
        ->get();
        $remarketingIntervalMessages = RemarketingIntervalMessages::selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->groupBy('date')
        ->get();
        $data['averageResponseRatef7t15'] = 0;
        $data['averageResponseRatef15t23'] = 0;
        $totalf7t15 = 0;
        $totalf15t23 = 0;
        foreach ($messagesPerDayHours as $messages) {
            $ids_array = explode(',', $messages->ids_list);
            $total_response_time = 0;
            $message_count = 0;
            
            foreach($ids_array as $id) {
                $message = FacebookMessage::find($id);
                if ($message)
                {
                    $total_response_time += $message->Response_Time();
                    $message_count++;
                }
            }
            if ($message_count > 0) {
                $average_response_time = $total_response_time / $message_count;
                
            } else {
                $average_response_time = 0;
            }
            $data['responseTime'][$messages->message_hour][date("j F", strtotime($messages->date))] = number_format($average_response_time, 2);
            if($messages->message_hour>=7 && $messages->message_hour<15)
            {
                $totalf7t15 += 1;
                $data['averageResponseRatef7t15'] += $average_response_time;
            }
            elseif($messages->message_hour>=15 && $messages->message_hour<=23 && $messages->message_hour != 19)
            {
                $totalf15t23 += 1; 
                $data['averageResponseRatef15t23'] += $average_response_time;
            }
        }
        $data['averageResponseRatef7t15'] = number_format($data['averageResponseRatef7t15']/$totalf7t15, 2);
        $data['averageResponseRatef15t23'] = number_format($data['averageResponseRatef15t23']/$totalf15t23, 2);
        

        foreach($remarketingMessages as $remarketingMessage)
        {
            if(isset($data[date("j F", strtotime($remarketingMessage['date']))]['total']))
            {
                $data['remarketingMessages'][date("j F", strtotime($remarketingMessage['date']))]['total'] += $remarketingMessage['count'];
            }
            else
            {
                $data['remarketingMessages'][date("j F", strtotime($remarketingMessage['date']))]['total'] = $remarketingMessage['count'];
                $data['remarketingIntervalMessages'][date("j F", strtotime($remarketingMessage['date']))]['total_interval'] = 0;
            }
        }
        foreach($remarketingIntervalMessages as $remarketingMessage)
        {
            if(isset($data[date("j F", strtotime($remarketingMessage['date']))]['total_interval']))
            {
                $data['remarketingIntervalMessages'][date("j F", strtotime($remarketingMessage['date']))]['total_interval'] += $remarketingMessage['count'];
            }
            else
            {
                $data['remarketingIntervalMessages'][date("j F", strtotime($remarketingMessage['date']))]['total_interval'] = $remarketingMessage['count'];
            }
        }
        return view('pages.dashboard.index2')->with('data', $data);
        $remarketingMessages = RemarketingMessages::selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->groupBy('date')
        ->get();
        foreach($remarketingMessages as $remarketingMessage)
        {
            if(isset($data[date("j F", strtotime($remarketingMessage['date']))]['total']))
            {
                $data[date("j F", strtotime($remarketingMessage['date']))]['total'] += $remarketingMessage['count'];
            }
            else
            {
                $data[date("j F", strtotime($remarketingMessage['date']))]['total'] = $remarketingMessage['count'];
            }
        }
        foreach($data  as $date=>$elem)
        {
            $start_date = date('Y-m-d', strtotime($date));
            $end_date = date('Y-m-d', strtotime("$date +1 day"));
            $responses  = DB::select("SELECT DATE(FM.created_at) AS day, COUNT(*) AS conversation_count
            FROM remarketing_messages RM
            JOIN facebook_messages FM ON RM.facebook_conversation_id = FM.conversation
            JOIN remarketings RS ON RS.id = RM.remarketing
            WHERE FM.sented_from = 'user'
            AND FM.created_at > '$start_date'
            AND FM.created_at < '$end_date'
            AND RM.last_use < FM.created_at
            AND RM.last_use = (
                SELECT MAX(last_use)
                FROM remarketing_messages RM2
                WHERE RM2.remarketing = RM.remarketing
                AND RM2.facebook_conversation_id = RM.facebook_conversation_id
            )
            GROUP BY DATE(FM.created_at), FM.conversation;
            ");
            foreach ($responses as $response) {
                $formatted_date = date("j F", strtotime($response->day));
                if (isset($data[$formatted_date]['responses'])) {
                    $data[$formatted_date]['responses'] += $response->conversation_count;
                } else {
                    $data[$formatted_date]['responses'] = $response->conversation_count;
                }
            }
        }
        $orders  = DB::select("SELECT DATE(FM.created_at) as day, COUNT(*) as orders_count
            FROM remarketing_messages RM
            JOIN facebook_messages FM ON RM.facebook_conversation_id = FM.conversation
            JOIN remarketings RS ON RS.id = RM.remarketing
            WHERE FM.`message` LIKE  '%سجلت الطلبية تاعك خلي برك الهاتف مفتوح باه يعيطلك الليفرور و ما تنساش الطلبية على خاطر رانا نخلصو عليها جزاك الله%'
            AND RM.facebook_conversation_id = FM.conversation
            AND RM.last_use < FM.created_at
            AND RM.last_use = (
                SELECT MAX(last_use)
                FROM remarketing_messages
                WHERE remarketing = RM.remarketing
                AND RM.facebook_conversation_id = facebook_conversation_id
            )
            GROUP BY DATE(FM.created_at);
        ");
        foreach($orders  as $order)
        {
            if(isset($data[date("j F", strtotime($order->day))]['orders']))
            {
                $data[date("j F", strtotime($order->day))]['orders'] += $order->orders_count;
            }
            else
            {
                $data[date("j F", strtotime($order->day))]['orders'] = $order->orders_count;
            }
        }
        return view('pages.dashboard.index')->with('data', $data);
    }
}
