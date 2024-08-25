<?php

declare(strict_types=1);

namespace Test\src\Handler\Community;

use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class CommunityListPageHandlerTest extends AbstractTest
{
    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testCommunityListPageHandlerSuccess(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/community/1']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Сообщества/', $response->getBody());
        self::assertMatchesRegularExpression('/Diablo 2: База знаний/', $response->getBody());
    }
}
