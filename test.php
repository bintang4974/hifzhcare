<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$c = app()->make(App\Http\Controllers\HafalanController::class);
$ref = new ReflectionMethod($c, 'getMonthlyCalendar');
$ref->setAccessible(true);
dump($ref->invoke($c, 6));
