<?php

namespace App\Middleware;

use Contributte\Middlewares\Exception\InvalidStateException;
use Contributte\Psr7\Psr7Response;
use Contributte\Psr7\Psr7ServerRequest;
use Nette\Application\BadRequestException;
use Nette\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Tuupola\Http\Factory\ResponseFactory;

class PresenterMiddleware extends \Contributte\Middlewares\PresenterMiddleware
{
    public function __invoke(
        ServerRequestInterface $psr7Request,
        ResponseInterface $psr7Response,
        callable $next
    ): ResponseInterface {
        if (!($psr7Request instanceof Psr7ServerRequest)) {
            throw new InvalidStateException(
                sprintf('Invalid request object given. Required %s type.', Psr7ServerRequest::class)
            );
        }

        if (!($psr7Response instanceof Psr7Response)) {
            throw new InvalidStateException(
                sprintf('Invalid response object given. Required %s type.', Psr7Response::class)
            );
        }

        $applicationResponse = null;

        try {
            $applicationResponse = $this->processRequest($this->createInitialRequest($psr7Request));
        } catch (Throwable $e) {
            $errorPresenter = $this->errorPresenter;
            if (!$this->catchExceptions || $errorPresenter === null) {
                throw $e;
            }

            try {
                // Create a new response with given code
                $psr7Response = $psr7Response->withStatus(
                    $e instanceof BadRequestException ? ($e->getCode() !== 0 ? $e->getCode() : 404) : 500
                );
                // Try resolve exception via forward or redirect
                $applicationResponse = $this->processException($e, $errorPresenter);
            } catch (Throwable $e) {
                // No fallback needed
            }
            return $psr7Response->withApplicationResponse($applicationResponse);
        }

        $psr7Response = $psr7Response->withApplicationResponse($applicationResponse);

        return $next($psr7Request, $psr7Response);
    }
}