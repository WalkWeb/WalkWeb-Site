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
     * @throws AppException
     */
    public function testCommunityRepositoryJoinNew(string $accountId, string $communityId): void
    {
        self::assertEquals([], $this->getData($accountId, $communityId));

        $this->getRepository()->join($accountId, $communityId);

        $data = $this->getData($accountId, $communityId);

        self::assertEquals($accountId, $data['account_id']);
        self::assertEquals($communityId, $data['community_id']);
        self::assertEquals(1, $data['active']);
    }

    /**
     * @throws AppException
     */
    public function testCommunityRepositoryJoinOld(): void
    {
        $accountId = '1e3a3b27-12da-4c73-a3a7-b83092705b12';
        $communityId = '19b2d329-4ca0-4c07-8fb5-18a3a3e80001';

        $data = $this->getData($accountId, $communityId);

        self::assertEquals($accountId, $data['account_id']);
        self::assertEquals($communityId, $data['community_id']);
        self::assertEquals(0, $data['active']);

        $this->getRepository()->join($accountId, $communityId);

        $data = $this->getData($accountId, $communityId);

        self::assertEquals($accountId, $data['account_id']);
        self::assertEquals($communityId, $data['community_id']);
        self::assertEquals(1, $data['active']);
    }

    /**
     * @throws AppException
     */
    public function testCommunityRepositoryLeaveSuccess(): void
    {
        $accountId = '1e3a3b27-12da-4c73-a3a7-b83092705b11';
        $communityId = '19b2d329-4ca0-4c07-8fb5-18a3a3e80001';

        $data = $this->getData($accountId, $communityId);

        self::assertEquals($accountId, $data['account_id']);
        self::assertEquals($communityId, $data['community_id']);
        self::assertEquals(1, $data['active']);

        $this->getRepository()->leave($accountId, $communityId);

        $data = $this->getData($accountId, $communityId);

        self::assertEquals($accountId, $data['account_id']);
        self::assertEquals($communityId, $data['community_id']);
        self::assertEquals(0, $data['active']);
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
            ],
            [
                self::DEMO_MODERATOR,
                '19b2d329-4ca0-4c07-8fb5-18a3a3e80002',
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
