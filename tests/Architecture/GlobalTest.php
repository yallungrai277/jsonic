<?php

arch()->preset()->php();
arch()->preset()->security()->ignoring('md5');

arch()
    ->expect('App')
    ->not->toUse(['die', 'dd', 'dump']);

arch()
    ->expect('App\Models')
    ->toBeClasses()
    ->toExtend('Illuminate\Database\Eloquent\Model')
    ->ignoring('App\Models\User');

arch()
    ->expect('App\Enums\*')
    ->toBeEnums();

arch()
    ->expect('App\Dto\*')
    ->toBeClasses()
    ->toExtendNothing();

arch()
    ->expect('App\Helpers')
    ->toBeClasses()
    ->toExtendNothing();

arch()
    ->expect('App\Services\*')
    ->toBeClasses()
    ->toExtendNothing()
    ->ignoring('App\Services\Ai\EmbeddingService');

arch()
    ->expect('App\Console\Commands')
    ->toBeClasses()
    ->toExtend('Illuminate\Console\Command');

arch()
    ->expect('App\Actions\*')
    ->toBeClasses()
    ->toExtendNothing();

arch()
    ->expect('App\Providers')
    ->toBeClasses()
    ->toExtend('Illuminate\Support\ServiceProvider');
