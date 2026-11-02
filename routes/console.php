<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('gudang:info', function () {
    $this->info('GudangCerdas siap membantu pengelolaan inventaris.');
})->purpose('Menampilkan informasi singkat aplikasi GudangCerdas');
