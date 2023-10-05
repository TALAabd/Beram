<?php

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/storage-link', function () {
    $i = Artisan::call('storage:link');
    return $i;
});

Route::get('/seed', function () {
    $i = Artisan::call('db:seed');
    return $i;
});

Route::get('/optimize-clear', function () {
    $i = Artisan::call('optimize:clear');
    return $i;
});
Route::get('/composer-update', function () {
    // Define the Composer update command
    $command = 'composer update';

    // Create a Symfony Process to run the Composer update command
    $process = Process::fromShellCommandline($command);

    // Run the Composer update command
    $process->run();
});
