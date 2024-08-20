<?php

declare(strict_types=1);

namespace Test\src\Handler\Rating;

use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountCarmaRatingPageHandlerTest extends AbstractTest
{
    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testAccountCarmaRatingPageHandler(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/top/account/carma']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Пользователи с наибольшей кармой/', $response->getBody());
    }
}
