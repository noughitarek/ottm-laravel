<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DashboardResponseTime extends Model
{
    use HasFactory;
    protected $fillable = ["minute", "page", "value"];

    public static function Range($hours)
    {
        $i = 0;
        $total = 0;
        foreach(self::Hourly() as $hour)
        {
            if(in_array($hour->time, $hours))
            {
                $total += $hour->average;
                $i++;
            }
        }

        if($i!=0)
        {
            $avg = $total/$i;
            $min = floor($avg);
            $sec = round(($avg - $min) * 60);
            return $min." Min ".$sec." Sec";
        }
        else
        {
            return "n/a";
        }
    }
    public static function Get_Date()
    {
        $type = $_GET['type']??'Hourly';
        if($type == 'Hourly')
        {
            return self::Hourly();
        }
        elseif($type == 'Minutely')
        {
            return self::Minutely();
        }
        elseif($type == 'Daily')
        {
            return self::Daily();
        }
    }
    public static function defaultDateTime()
    {
        return Carbon::now()->subDay().' to '.Carbon::now();
    }
    public static function Hourly()
    {
        $startdate = Carbon::now()->subDay();
        $enddate = Carbon::now();

        if(isset($_GET['datetime']))
        {
            $startdate = explode(' to ', $_GET['datetime'])[0]??Carbon::now()->subDay();
            $enddate = explode(' to ', $_GET['datetime'])[1]??Carbon::now();
        }
        $page = $_GET['page']??0;

        if($page != null)
        {
            return self::selectRaw('HOUR(minute) AS time, avg(value) as average')
            ->where('minute', '>=', Carbon::parse($startdate))
            ->where('minute', '<=', Carbon::parse($enddate))
            ->where('page', $page)
            ->groupBy('time')
            ->orderBy('time', 'asc')
            ->get();
        }
        else 
        {
            return self::selectRaw('HOUR(minute) AS time, avg(value) as average')
            ->where('minute', '>=', Carbon::parse($startdate))
            ->where('minute', '<=', Carbon::parse($enddate))
            ->groupBy('time')
            ->orderBy('time', 'asc')
            ->get();
        }
    }
    public static function Minutely()
    {   
        $startdate = Carbon::now()->subDay();
        $enddate = Carbon::now();
        if(isset($_GET['datetime']))
        {
            $startdate = explode(' to ', $_GET['datetime'])[0]??Carbon::now()->subDay();
            $enddate = explode(' to ', $_GET['datetime'])[1]??Carbon::now();
        }
        $page = isset($_GET['page']) & $_GET['page']!=null?$_GET['page']:0;

        if($page != 0)
        {
            return self::selectRaw('Minute(minute) AS time, avg(value) as average')
            ->where('minute', '>=', Carbon::parse($startdate))
            ->where('minute', '<=', Carbon::parse($enddate))
            ->where('page', $page)
            ->groupBy('time')
            ->orderBy('time', 'asc')
            ->get();
        }
        else 
        {
            return self::selectRaw('Minute(minute) AS time, avg(value) as average')
            ->where('minute', '>=', Carbon::parse($startdate))
            ->where('minute', '<=', Carbon::parse($enddate))
            ->groupBy('time')
            ->orderBy('time', 'asc')
            ->get();
        }
    }
    public static function Daily()
    {
        $startdate = Carbon::now()->subMonth();
        $enddate = Carbon::now();
        if(isset($_GET['datetime']))
        {
            $startdate = explode(' to ', $_GET['datetime'])[0]??Carbon::now()->subMonth();
            $enddate = explode(' to ', $_GET['datetime'])[1]??Carbon::now();
        }
        $page = $_GET['page']??0;

        return self::selectRaw('Day(minute) AS time, avg(value) as average')
        ->where('minute', '>=', Carbon::now()->subMonth())
        ->groupBy('time')
        ->orderBy('time', 'asc')
        ->get();
    }
}
