<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;



Schedule::command('app:responder-send-command')->everyMinute()->runInBackground();
Schedule::command('app:upload-orders-command')->hourly()->runInBackground();
Schedule::command('app:update-orders-states')->everyTenMinutes()->runInBackground();
Schedule::command('app:get-conversations')->everyMinute()->runInBackground();
Schedule::command('app:remarketing-send')->everyTenMinutes()->runInBackground();
Schedule::command('app:remarketing-interval-send')->everyMinute()->runInBackground();
Schedule::command('app:update-response-time-dashboard')->hourly()->runInBackground();

#Schedule::command('app:tokens-validity-check')->hourly();
#Schedule::command('app:get-all-conversations')->everyMinute();
#Schedule::command('queue:work')->hourly();