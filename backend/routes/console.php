<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment('Portal Update Manager ready.');
})->purpose('Display an inspirational message to verify console wiring');
