<?php

declare(strict_types=1);

namespace Test\src\Handler;

use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class StatisticPageHandlerTest extends AbstractTest
{
    /**
     * Test on get statistic page
     *
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testStatisticPageHandlerSuccess(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/statistic']);
        $response = $this->createApp($template)->handle($request);

        self::assertMatchesRegularExpression('/Статистика/', $response->getBody());
        self::assertMatchesRegularExpression('/Пользователей: 11/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());
    }
}
