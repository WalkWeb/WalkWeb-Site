<?php

declare(strict_types=1);

namespace Test\src\Handler\Info;

use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class FunctionalityPageHandlerTest extends AbstractTest
{
    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testFunctionalityPageHandler(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/functionality']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Функционал проекта/', $response->getBody());
    }
}
