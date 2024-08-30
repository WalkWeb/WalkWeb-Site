<?php

declare(strict_types=1);

namespace Test\src\Domain\Community;

use App\Domain\Community\CommunityException;
use App\Domain\Community\CommunityRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CommunityRepositoryTest extends AbstractTest
{
    /**
     * @dataProvider getDataProvider
     * @param string $slug
     * @param bool $isJoined
     * @param string|null $token
     * @throws AppException
     */
    public function testCommunityRepositoryGetAuth(string $slug, bool $isJoined, ?string $token = null): void
    {
        $user = $token ? $this->getUser($token) : null;

        $community = $this->getRepository()->get($slug, $user);

        self::assertEquals($slug, $community->getSlug());
        self::assertEquals($isJoined, $community->isJoined());
    }

    /**
     * @throws AppException
     */
    public function testCommunityRepositoryGetNotFoundAuth(): void
    {
        self::assertNull(
            $this->getRepository()->get('not-found', $this->getUser('VBajfT8P6PFtrkHhCqb7ZNwIFG4a11'))
        );
    }

    /**
     * @dataProvider getAllDataProvider
     * @param array $expectedNames
     * @throws AppException
     */
    public function testCommunityRepositoryGetAll(array $expectedNames): void
    {
        $communities = $this->getRepository()->getAll();

        self::assertCount(6, $communities);

        $i = 0;
        foreach ($communities as $community) {
            self::assertEquals($expectedNames[$i], $community->getName());
            $i++;
        }
    }

    /**
     * @dataProvider joinNewDataProvider
     * @param string $accountId
     * @param string $communityId
     * @param int $followers
     * @throws AppException
     */
    public function testCommunityRepositoryJoinNew(string $accountId, string $communityId, int $followers): void
    {
        self::assertEquals($followers, $this->getCommunityData($communityId)['followers']);

        self::assertEquals([], $this->getLinkData($accountId, $communityId));

        $this->getRepository()->join($accountId, $communityId);

        $data = $this->getLinkData($accountId, $communityId);

        self::assertEquals($accountId, $data['account_id']);
        self::assertEquals($communityId, $data['community_id']);
        self::assertEquals(1, $data['active']);

        self::assertEquals($followers + 1, $this->getCommunityData($communityId)['followers']);
    }

    /**
     * @throws AppException
     */
    public function testCommunityRepositoryJoinOld(): void
    {
        $accountId = '1e3a3b27-12da-4c73-a3a7-b83092705b12';
        $communityId = '19b2d329-4ca0-4c07-8fb5-18a3a3e80001';

        self::assertEquals(165, $this->getCommunityData($communityId)['followers']);

        $data = $this->getLinkData($accountId, $communityId);

        self::assertEquals($accountId, $data['account_id']);
        self::assertEquals($communityId, $data['community_id']);
        self::assertEquals(0, $data['active']);

        $this->getRepository()->join($accountId, $communityId);

        $data = $this->getLinkData($accountId, $communityId);

        self::assertEquals($accountId, $data['account_id']);
        self::assertEquals($communityId, $data['community_id']);
        self::assertEquals(1, $data['active']);

        self::assertEquals(165 + 1, $this->getCommunityData($communityId)['followers']);
    }

    /**
     * @throws AppException
     */
    public function testCommunityRepositoryLeaveSuccess(): void
    {
        $accountId = '1e3a3b27-12da-4c73-a3a7-b83092705b11';
        $communityId = '19b2d329-4ca0-4c07-8fb5-18a3a3e80001';

        self::assertEquals(165, $this->getCommunityData($communityId)['followers']);

        $data = $this->getLinkData($accountId, $communityId);

        self::assertEquals($accountId, $data['account_id']);
        self::assertEquals($communityId, $data['community_id']);
        self::assertEquals(1, $data['active']);

        $this->getRepository()->leave($accountId, $communityId);

        $data = $this->getLinkData($accountId, $communityId);

        self::assertEquals($accountId, $data['account_id']);
        self::assertEquals($communityId, $data['community_id']);
        self::assertEquals(0, $data['active']);

        self::assertEquals(165 - 1, $this->getCommunityData($communityId)['followers']);
    }

    /**
     * @throws AppException
     */
    public function testCommunityRepositoryLeaveNotFound(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage(CommunityException::MEMBER_NOT_FOUND);
        $this->getRepository()->leave('1e3a3b27-12da-4c73-a3a7-b83092705b11', '749fbc18-55f2-41b8-b341-265b24e05b97');
    }

    /**
     * @dataProvider getNameDataProvider
     * @param string $slug
     * @param string|null $name
     * @throws AppException
     */
    public function testCommunityRepositoryGetName(string $slug, ?string $name): void
    {
        self::assertEquals($name, $this->getRepository()->getName($slug));
    }

    /**
     * @return array
     */
    public function getDataProvider(): array
    {
        return [
            // no auth
            [
                'diablo-2-wiki',
                false,
                null,
            ],
            // no join
            [
                'diablo-2-wiki',
                false,
                'VBajfT8P6PFtrkHhCqb7ZNwIFG4a14',
            ],
            // active = 0
            [
                'diablo-2-wiki',
                false,
                'VBajfT8P6PFtrkHhCqb7ZNwIFG4a12',
            ],
            // active = 1
            [
                'diablo-2-wiki',
                true,
                'VBajfT8P6PFtrkHhCqb7ZNwIFG4a11',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getAllDataProvider(): array
    {
        return [
            [
                [
                    'Diablo 2: База знаний',
                    'Ведьмак 3: База знаний',
                    'Fallout 4: База знаний',
                    'Скайрим: База знаний',
                    'Path of Exile: База знаний',
                    'Divine Divinity',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function joinNewDataProvider(): array
    {
        return [
            [
                self::DEMO_USER,
                '19b2d329-4ca0-4c07-8fb5-18a3a3e80001',
                165,
            ],
            [
                self::DEMO_MODERATOR,
                '19b2d329-4ca0-4c07-8fb5-18a3a3e80002',
                95,
            ],
        ];
    }

    /**
     * @return array
     */
    public function getNameDataProvider(): array
    {
        return [
            [
                'diablo-2-wiki',
                'Diablo 2: База знаний',
            ],
            [
                'no-community',
                null,
            ],
        ];
    }

    /**
     * @return CommunityRepository
     * @throws AppException
     */
    private function getRepository(): CommunityRepository
    {
        return new CommunityRepository(self::getContainer());
    }

    /**
     * @param string $accountId
     * @param string $communityId
     * @return array
     * @throws AppException
     */
    private function getLinkData(string $accountId, string $communityId): array
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

    /**
     * @param string $communityId
     * @return array
     * @throws AppException
     */
    private function getCommunityData(string $communityId): array
    {
        return self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `communities` WHERE `id` = ?',
            [
                ['type' => 's', 'value' => $communityId],
            ],
            true
        );
    }
}
