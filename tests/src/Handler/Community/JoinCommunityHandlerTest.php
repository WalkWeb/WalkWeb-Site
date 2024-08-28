<?php

declare(strict_types=1);

namespace Test\src\Handler\Community;

use App\Domain\Account\AccountInterface;
use App\Domain\Community\CommunityException;
use App\Handler\Community\JoinCommunityHandler;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class JoinCommunityHandlerTest extends AbstractTest
{
    /**
     * @throws AppException
     */
    public function testJoinCommunityHandlerNew(): void
    {
        $accountId = '1e3a3b27-12da-4c73-a3a7-b83092705b01';
        $communityId = '19b2d329-4ca0-4c07-8fb5-18a3a3e80001';
        self::assertEquals([], $this->getData($accountId, $communityId));

        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(
            ['REQUEST_URI' => '/community/join/diablo-2-wiki', 'REQUEST_METHOD' => 'POST'],
            [],
            [AccountInterface::AUTH_TOKEN => $token]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertEquals(['success' => true], self::jsonDecode($response->getBody()));

        $data = $this->getData($accountId, $communityId);

        self::assertEquals($accountId, $data['account_id']);
        self::assertEquals($communityId, $data['community_id']);
        self::assertEquals(1, $data['active']);
    }

    /**
     * @throws AppException
     */
    public function testJoinCommunityHandlerOld(): void
    {
        $accountId = '1e3a3b27-12da-4c73-a3a7-b83092705b12';
        $communityId = '19b2d329-4ca0-4c07-8fb5-18a3a3e80001';

        $data = $this->getData($accountId, $communityId);

        self::assertEquals($accountId, $data['account_id']);
        self::assertEquals($communityId, $data['community_id']);
        self::assertEquals(0, $data['active']);

        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG4a12';
        $request = new Request(
            ['REQUEST_URI' => '/community/join/diablo-2-wiki', 'REQUEST_METHOD' => 'POST'],
            [],
            [AccountInterface::AUTH_TOKEN => $token]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertEquals(['success' => true], self::jsonDecode($response->getBody()));

        $data = $this->getData($accountId, $communityId);

        self::assertEquals($accountId, $data['account_id']);
        self::assertEquals($communityId, $data['community_id']);
        self::assertEquals(1, $data['active']);
    }

    /**
     * @throws AppException
     */
    public function testJoinCommunityHandlerNoAuth(): void
    {
        $request = new Request(
            ['REQUEST_URI' => '/community/join/diablo-2-wiki', 'REQUEST_METHOD' => 'POST'],
        );

        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(JoinCommunityHandler::NO_AUTH, $response);
    }

    /**
     * @throws AppException
     */
    public function testJoinCommunityHandlerUnknownCommunity(): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(
            ['REQUEST_URI' => '/community/join/unknown-community', 'REQUEST_METHOD' => 'POST'],
            [],
            [AccountInterface::AUTH_TOKEN => $token]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(CommunityException::NOT_FOUND, $response);
    }

    /**
     * @param string $accountId
     * @param string $communityId
     * @return array
     * @throws AppException
     */
    public function getData(string $accountId, string $communityId): array
    {
        return self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `lk_account_community` WHERE `account_id` = ? AND `community_id` = ?',
            [
                ['type' => 's', 'value' => $accountId],
                ['type' => 's', 'value' => $communityId],
            ],
            true
        );
    }
}
