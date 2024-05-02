<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\BotsEngine;
use Illuminate\Http\Request;

class BotsEngineController extends Controller
{
    public function botsengine()
    {
        $latestCreatedAt = Carbon::parse(BotsEngine::max('created_at'));
        $fiveMinutesAgo = Carbon::now()->addHour()->subMinutes(5);
        $engine = $latestCreatedAt->gte($fiveMinutesAgo);
        $logs = BotsEngine::orderBy('created_at', 'asc')->limit(100)->get();
        return view('pages.bots_engine')->with('logs', $logs)->with('engine', $engine);
    }
}
