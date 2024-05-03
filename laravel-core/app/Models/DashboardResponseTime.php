<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DashboardResponseTime extends Model
{
    use HasFactory;
    protected $fillable = ["minute", "page", "value"];
    public static function Get_Date($type)
    {
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
    public static function Hourly()
    {
        return self::selectRaw('HOUR(minute) AS time, avg(value) as average')
        ->where('minute', '>=', Carbon::now()->subDay())
        ->groupBy('time')
        ->orderBy('time', 'asc')
        ->get();
    }
    public static function Minutely()
    {
        return self::selectRaw('Minute(minute) AS time, avg(value) as average')
        ->where('minute', '>=', Carbon::now()->subDay())
        ->groupBy('time')
        ->orderBy('time', 'asc')
        ->get();
    }
    public static function Daily()
    {
        return self::selectRaw('Day(minute) AS time, avg(value) as average')
        ->where('minute', '>=', Carbon::now()->subMonth())
        ->groupBy('time')
        ->orderBy('time', 'asc')
        ->get();
    }
}
