<?php

use App\Connectors\BaseConnector;
use App\Connectors\JokeConnector;

arch()
    ->expect(BaseConnector::class)
    ->toBeAbstract();

arch()
    ->expect(JokeConnector::class)
    ->toBeAbstract()
    ->toExtend(BaseConnector::class);

arch()
    ->expect('App\Connectors\Jokes')
    ->toExtend(JokeConnector::class);
