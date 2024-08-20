<?php

declare(strict_types=1);

namespace Test\src\Handler\Info;

use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class RulesPageHandlerTest extends AbstractTest
{
    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testRulesPageHandlerSuccess(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/rules']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Правила/', $response->getBody());
    }
}
