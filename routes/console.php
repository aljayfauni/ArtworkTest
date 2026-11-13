<?php

use App\Console\Commands\UpdateFYStatus;
use App\Console\Commands\UpdatePIStatus;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command(UpdateFYStatus::class)->dailyAt('00:00')->withoutOverlapping();
Schedule::command(UpdatePIStatus::class)->hourly()->withoutOverlapping();
