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
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testCharacterPageHandlerSuccess(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/c/277bbc70-cb4a-49a9-8de2-3fd5c1308c01']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Analyst/', $response->getBody());
        self::assertMatchesRegularExpression('/Default/', $response->getBody());
    }

    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testCharacterPageHandlerNotFound(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/c/277bbc70-cb4a-49a9-8de2-3fd5c1308c33']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Персонаж не найден/', $response->getBody());
    }
}
