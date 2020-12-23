<?php

namespace Accolon\EventLoop;

class Loop
{
    private array $events = [];

    private function timer(float $time, callable $callback, bool $repeat)
    {
        $uid = md5(uniqid(microtime(true), true));

        $this->events[$uid] = [
            'period' => $time / 1000,
            'repeat' => $repeat,
            'time' => microtime(true) + ($time / 1000),
            'callback' => $callback,
            'id' => $uid
        ];
    }

    public function addTimer(float $time, callable $callback)
    {
        $this->timer($time, $callback, false);
    }

    public function addPeriodicTimer(float $time, callable $callback)
    {
        $this->timer($time, $callback, true);
    }

    public function cancelTimer(string $id)
    {
        unset($this->events[$id]);
    }

    public function futureTick(callable $callback)
    {
        $this->addTimer(0, $callback);
    }

    public function run()
    {
        while (true) {
            $time = microtime(true);
            // var_dump($time);
            foreach ($this->events as $id => $timer) {
                // dd($timer['time'], $time);
                if ($timer['time'] <= $time) {
                    if (!$timer['repeat']) {
                        unset($this->events[$id]);
                    } else {
                        $this->events[$id]['time'] += $timer['period'];
                    }

                    $timer['callback']($timer);
                }
            }
        }
    }
}
