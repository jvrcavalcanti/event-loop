<?php

use Accolon\EventLoop\Loop;

require_once './vendor/autoload.php';

function dd(...$vars)
{
    foreach ($vars as $var) {
        var_dump($var);
    }
    exit;
}

$loop = new Loop;

$loop->addPeriodicTimer(1000, function ($timer) use ($loop) {
    static $count = 0;
    $count ++;
    echo 'Count: ' . $count . PHP_EOL;

    if ($count >= 5) {
        $loop->cancelTimer($timer['id']);
        $loop->futureTick(function () use ($loop) {
            echo 'Done' . PHP_EOL;
        });
    }
});

$loop->run();
