<?php

declare(strict_types=1);

namespace Test\src\Handler;

use App\Domain\Account\AccountInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class MainHandlerTest extends AbstractTest
{
    /**
     * Тест на получение главной страницы
     *
     * @throws AppException
     */
    public function testMainPageSuccess(): void
    {
        $request = new Request(['REQUEST_URI' => '/']);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Заголовок поста #1/', $response->getBody());
    }

    /**
     * Тест на ситуацию, когда заблокированный пользователь пытается открыть главную страницу - его переадресует на
     * страницу с информацией о том, что его аккаунт заблокирован
     *
     * @throws AppException
     */
    public function testMainPageBannedUser(): void
    {
        $request = new Request(['REQUEST_URI' => '/'], [], [AccountInterface::AUTH_TOKEN => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a2']);
        $response = $this->app->handle($request);

        self::assertEquals(Response::FOUND, $response->getStatusCode());
        self::assertEquals('/banned', $response->getHeaders()['Location']);
    }

    /**
     * Проверяем ответ о несуществующей странице
     *
     * @throws AppException
     */
    public function testNotFoundPage(): void
    {
        $request = new Request(['REQUEST_URI' => '/no_page']);
        $response = $this->app->handle($request);

        $expectedContent = <<<EOT
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Ошибка 404: Страница не найдена</title>
    <meta name="Description" content="">
    <meta name="Keywords" content="">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
</head>
<body>
<div class="content">
    <h1>Ошибка 404: Страница не найдена</h1>
</body>
</html>
EOT;

        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
        self::assertEquals($expectedContent, $response->getBody());
    }
}
