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

        self::assertMatchesRegularExpression('/WalkWeb Site/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());
    }
}
