<?php

declare(strict_types=1);

namespace Test\src\Middleware;

use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;

class StatisticsMiddlewareTest extends AbstractTest
{
    /**
     * Test on StatisticsMiddleware
     *
     * @throws AppException
     */
    public function testStatisticsMiddleware(): void
    {
        $request = new Request(['REQUEST_URI' => '/']);
        $response = $this->createApp()->handle($request);

        self::assertCount(1, $response->getHeaders());

        foreach ($response->getHeaders() as $header => $value) {
            self::assertEquals('Statistic', $header);
            self::assertMatchesRegularExpression('/Runtime: /', $value);
            self::assertMatchesRegularExpression('/memory cost: /', $value);
            self::assertMatchesRegularExpression('/queries: 1/', $value);
        }
    }
}
