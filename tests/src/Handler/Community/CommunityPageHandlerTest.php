<?php

declare(strict_types=1);

namespace Test\src\Handler\Community;

use App\Domain\Account\AccountInterface;
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
     * @param string $post
     * @throws AppException
     */
    public function testCommunityPageHandlerAuthSuccess(string $template, string $slug, string $name, string $post): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a7';
        $request = new Request(['REQUEST_URI' => "/c/$slug"], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression("/$name/", $response->getBody());
        self::assertMatchesRegularExpression("/$post/", $response->getBody());
        self::assertMatchesRegularExpression('/Добавить пост/', $response->getBody());
    }

    /**
     * @dataProvider successDataProvider
     * @param string $template
     * @param string $slug
     * @param string $name
     * @param string $post
     * @throws AppException
     */
    public function testCommunityPageHandlerNoAuthSuccess(string $template, string $slug, string $name, string $post): void
    {
        $request = new Request(['REQUEST_URI' => "/c/$slug"]);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression("/$name/", $response->getBody());
        self::assertMatchesRegularExpression("/$post/", $response->getBody());
        self::assertMatchesRegularExpression('/Добавить пост/', $response->getBody());
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
                'Title post 9',
            ],
            [
                'inferno',
                'diablo-2-wiki',
                'Diablo 2: База знаний',
                'Title post 9',
            ],
            [
                'default',
                'skyrim-wiki',
                'Скайрим: База знаний',
                'В сообществе пока нет материалов',
            ],
            [
                'inferno',
                'skyrim-wiki',
                'Скайрим: База знаний',
                'В сообществе пока нет материалов',
            ],
        ];
    }
}
