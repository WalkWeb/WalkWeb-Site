<?php

declare(strict_types=1);

namespace Test\src\Handler\Character;

use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class CharacterPageHandlerTest extends AbstractTest
{
    /**
     * @throws AppException
     */
    public function testCharacterPageHandlerSuccess(): void
    {
        $request = new Request(['REQUEST_URI' => '/c/277bbc70-cb4a-49a9-8de2-3fd5c1308c01']);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/WalkWeb — Просмотр информации о персонаже/', $response->getBody());
        self::assertMatchesRegularExpression('/ID: 277bbc70-cb4a-49a9-8de2-3fd5c1308c01/', $response->getBody());
    }

    /**
     * @throws AppException
     */
    public function testCharacterPageHandlerNotFound(): void
    {
        $request = new Request(['REQUEST_URI' => '/c/277bbc70-cb4a-49a9-8de2-3fd5c1308c33']);
        $response = $this->app->handle($request);

        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Персонаж не найден/', $response->getBody());
    }
}
