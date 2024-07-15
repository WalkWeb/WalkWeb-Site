<?php

declare(strict_types=1);

namespace App\Middleware;

use WalkWeb\NW\AbstractMiddleware;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class OnlyAuthMiddleware extends AbstractMiddleware
{
    /**
     * @param Request $request
     * @param callable $handler
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request, callable $handler): Response
    {
        if (!$this->container->exist('user')) {
            $response = new Response('', Response::FOUND);
            $response->withHeader('Location', '/login');
            return $response;
        }

        return $handler($request);
    }
}
