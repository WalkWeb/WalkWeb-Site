<?php

declare(strict_types=1);

namespace Test\Mock;

use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class TestHandler extends AbstractHandler
{
    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        return $this->render('index');
    }
}
