<?php

declare(strict_types=1);

namespace Test\src\Handler;

use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class StatisticHandlerTest extends AbstractTest
{
    /**
     * Test on get statistic page
     *
     * @throws AppException
     */
    public function testMainPage(): void
    {
        $request = new Request(['REQUEST_URI' => '/statistic']);
        $response = $this->app->handle($request);

        self::assertMatchesRegularExpression('/Статистика/', $response->getBody());
        self::assertMatchesRegularExpression('/Пользователей: 10/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());
    }
}
