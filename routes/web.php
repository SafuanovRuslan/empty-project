<?php

use Elastic\Elasticsearch\ClientBuilder;
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

Route::get('/test-elasticsearch', function () {
    $client = ClientBuilder::create()
        ->setHosts(['elasticsearch:9200'])
        ->build();

    $saveResult = $client->index([
        'index' => 'catalog',
        'id' => 1,
        'body' => ['title' => 'MacBook'],
    ])->asObject();

    $result = $client->get(['index' => 'catalog', 'id' => 1])->asObject();

    // learning

    $params = [
        'index' => 'lesson',
        'body' => [
            'settings' => [
                'number_of_shards' => 3,
                'number_of_replicas' => 2
            ],
            'mappings' => [
                '_source' => [
                    'enabled' => true
                ],
                'properties' => [
                    'first_name' => [
                        'type' => 'keyword'
                    ],
                    'age' => [
                        'type' => 'integer'
                    ]
                ]
            ]
        ]
    ];
    $client->indices()->create($params);

    $params = [
        'index' => 'lesson',
        'body' =>[
            'settings' => [
                'number_of_replicas' => 0,
            ]
        ]
    ];
    $client->indices()->putSettings($params);

    $result = $client->indices()->getSettings(['index' => 'lesson'])->asArray();

    $client->indices()->delete(['index' => 'lesson']);

    return $result;
});
