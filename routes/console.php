<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('deploy:finish')
    ->everyMinute()
    ->withoutOverlapping(10)
    ->evenInMaintenanceMode();
