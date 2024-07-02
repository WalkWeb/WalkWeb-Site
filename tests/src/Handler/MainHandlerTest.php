<?php

declare(strict_types=1);

namespace Tests\src\Handler;

use Tests\AbstractTest;
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
    public function testMainPage(): void
    {
        $request = new Request(['REQUEST_URI' => '/']);
        $response = $this->app->handle($request);

        self::assertMatchesRegularExpression('/Заголовок поста #1/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());
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

        self::assertEquals($expectedContent, $response->getBody());
        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
    }
}
