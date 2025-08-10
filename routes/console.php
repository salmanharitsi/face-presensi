<?php

use App\Jobs\StatusTidakHadirJob;
use App\Jobs\StatusTidakLaporPulangJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwalkan job StatusTidakHadirJob setiap hari pada jam 07.16
Schedule::job(new StatusTidakHadirJob())->dailyAt('07:16');

// Jadwalkan job StatusTidakLaporPulangJob setiap hari pada jam 08.02
Schedule::job(new StatusTidakLaporPulangJob())->dailyAt('18:02');
