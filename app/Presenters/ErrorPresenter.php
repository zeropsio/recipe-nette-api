<?php

declare(strict_types=1);

namespace App\Presenters;

use http\Exception\RuntimeException;
use Nette;
use Nette\Application\Responses;
use Nette\Http;
use Tracy\ILogger;


final class ErrorPresenter extends Nette\Application\UI\Presenter
{
    use Nette\SmartObject;

    /** @var ILogger */
    private $logger;


    public function __construct(ILogger $logger)
    {
        $this->logger = $logger;
    }


    public function run(Nette\Application\Request $request): Nette\Application\Response
    {
        $exception = $request->getParameter('exception');
        if ($exception instanceof Nette\Application\BadRequestException) {
            return new Responses\JsonResponse([
                "message" => $exception->getMessage(),
            ]);
        }

        $this->logger->log($exception, ILogger::EXCEPTION);
        return new Responses\JsonResponse([
            "message" => "internal server error"
        ]);
    }
}
