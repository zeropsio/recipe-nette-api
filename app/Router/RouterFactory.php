<?php

declare(strict_types=1);

namespace App\Router;

use Contributte\ApiRouter\ApiRoute;
use Nette\Application\Routers\RouteList;
use Nette\StaticClass;

final class RouterFactory
{
	use StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;

        $router->addRoute('/', 'Todos:index');

        $router[] = new ApiRoute('/todos', 'Todos', [
            'methods' => ['GET' => 'listing', 'POST' => 'create'],
        ]);

        $router[] = new ApiRoute('/todos/<id>', 'Todos', [
            'parameters' => [
                'id' => ['requirement' => '\d+']
            ],
            'methods' => ['GET' => 'detail', 'PATCH' => 'update', 'DELETE' => 'delete'],
            'priority' => 1
        ]);

		return $router;
	}
}
