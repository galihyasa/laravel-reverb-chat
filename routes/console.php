<?php

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment('Mini Real-Time Chat Laravel Reverb');
})->purpose('Display an inspiring message');
