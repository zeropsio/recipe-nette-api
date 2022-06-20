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

        //Tracy\Debugger::$logSeverity = E_ALL & ~E_NOTICE;
        Tracy\Debugger::setLogger(new class extends Tracy\Logger {
            public function __construct()
            {
                // intentionally do not call parent constructor, we don't actually need the parameters
            }

            public function log($value, $priority = self::INFO)
            {
                $log = match($priority){
                    self::DEBUG => LOG_DEBUG,
		            self::INFO => LOG_INFO,
                    self::WARNING => LOG_WARNING,
                    self::ERROR, self::EXCEPTION => LOG_ERR,
                    self::CRITICAL => LOG_CRIT,
                };

                syslog(LOG_LOCAL0 | $log, str_replace("\n", " ", $this->formatMessage($value)) . "\n");
            }
        });

        Tracy\Debugger::enable(Tracy\Debugger::PRODUCTION);

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
