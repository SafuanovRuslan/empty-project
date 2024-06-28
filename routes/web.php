<?php

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

Route::get('/test-redis', function () {
    \Illuminate\Support\Facades\Redis::set('test', 'hello');
    return \Illuminate\Support\Facades\Redis::get('test');
});

Route::get('/test-rabbitmq', function () {
    $connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
        env('RABBITMQ_DEFAULT_HOST'),
        env('RABBITMQ_DEFAULT_PORT'),
        env('RABBITMQ_DEFAULT_USER'),
        env('RABBITMQ_DEFAULT_PASS')
    );
    $chanel = $connection->channel();

    $chanel->queue_declare('hello');

    $msg = new \PhpAmqpLib\Message\AMQPMessage('Hello Project');
    $chanel->basic_publish($msg, '', 'hello');

    $result = $chanel->basic_get('hello');

    $chanel->close();
    $connection->close();

    return $result->body;
});
