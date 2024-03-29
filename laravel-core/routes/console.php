<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('app:get-conversations')->everyMinute();
Schedule::command('app:update-orders-states')->hourly();
Schedule::command('app:tokens-validity-check')->daily();