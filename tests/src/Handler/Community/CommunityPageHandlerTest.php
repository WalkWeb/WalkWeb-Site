<?php

declare(strict_types=1);

namespace Test\src\Handler\Community;

use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class CommunityPageHandlerTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param string $template
     * @param string $slug
     * @param string $name
     * @throws AppException
     */
    public function testCommunityPageHandlerSuccess(string $template, string $slug, string $name): void
    {
        $request = new Request(['REQUEST_URI' => "/c/$slug"]);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression("/$name/", $response->getBody());
    }

    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testCommunityPageHandlerNotFound(string $template): void
    {
        $request = new Request(['REQUEST_URI' => "/c/not-found"]);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Сообщество не найдено/', $response->getBody());
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                'default',
                'diablo-2-wiki',
                'Diablo 2: База знаний',
            ],
            [
                'inferno',
                'diablo-2-wiki',
                'Diablo 2: База знаний',
            ],
            [
                'default',
                'skyrim-wiki',
                'Скайрим: База знаний',
            ],
            [
                'inferno',
                'skyrim-wiki',
                'Скайрим: База знаний',
            ],
        ];
    }
}
