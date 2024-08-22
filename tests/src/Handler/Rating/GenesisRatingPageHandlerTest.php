<?php

declare(strict_types=1);

namespace Test\src\Handler\Rating;

use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class GenesisRatingPageHandlerTest extends AbstractTest
{
    /**
     * @throws AppException
     */
    public function testGenesisRatingPageHandlerITTheme(): void
    {
        $request = new Request(['REQUEST_URI' => '/top/account/genesis']);
        $response = $this->createApp('default')->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Рейтинг профессий/', $response->getBody());
    }

    /**
     * @throws AppException
     */
    public function testGenesisRatingPageHandlerGameTheme(): void
    {
        $request = new Request(['REQUEST_URI' => '/top/account/genesis']);
        $response = $this->createApp('inferno')->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Рейтинг рас/', $response->getBody());
    }
}
