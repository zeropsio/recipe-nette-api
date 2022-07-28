<?php

declare(strict_types=1);

namespace App;

use Nette\Bootstrap\Configurator;
use Tracy;


class Bootstrap
{
    public static function boot(): Configurator
    {
        $configurator = new Configurator;
        $appDir = dirname(__DIR__);

        //$configurator->setDebugMode('secret@23.75.345.200'); // enable for your remote IP
        $configurator->enableTracy($appDir . '/log');

        Tracy\Debugger::enable(Tracy\Debugger::DEBUG);

        $configurator->addParameters([
            'rootDir' => realpath(__DIR__ . '/..'),
        ]);

        $configurator->addDynamicParameters([
            'env' => getenv(),
        ]);

        $configurator->setTimeZone('Europe/Prague');
        $configurator->setTempDirectory($appDir . '/temp');

        $configurator->createRobotLoader()
            ->addDirectory(__DIR__)
            ->register();

        $configurator->addConfig($appDir . '/config/common.neon');
        $configurator->addConfig($appDir . '/config/services.neon');
        $configurator->addConfig($appDir . '/config/local.neon');

        return $configurator;
    }
}
