<?php

declare(strict_types=1);

namespace Test\src\Domain\Auth;

use App\Domain\Account\Notice\NoticeException;
use App\Domain\Account\Notice\NoticeFactory;
use App\Domain\Auth\AuthRepository;
use Exception;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class AuthRepositoryTest extends AbstractTest
{
    /**
     * Test on success get Auth
     *
     * @dataProvider successDataProvider
     * @param string $authToken
     * @param string $id
     * @param string $name
     * @param int $statusId
     * @param string $energyId
     * @param array $noticesData
     * @param array $level
     * @param string $avatar
     * @throws AppException
     * @throws NoticeException
     */
    public function testAuthRepositoryGetSuccess(
        string $authToken,
        string $id,
        string $name,
        int $statusId,
        string $energyId,
        array $noticesData,
        array $level,
        string $avatar
    ): void
    {
        $auth = $this->getRepository()->get($authToken, $this->getSendNoticeAction());

        self::assertEquals($id, $auth->getId());
        self::assertEquals($name, $auth->getName());
        self::assertEquals(10, $auth->getGroup()->getId());
        self::assertEquals($statusId, $auth->getStatus()->getId());
        self::assertTrue($auth->isCanLike());

        self::assertEquals($energyId, $auth->getEnergy()->getId());
        self::assertEquals(150, $auth->getEnergy()->getEnergy());
        self::assertEquals(150, $auth->getEnergy()->getMaxEnergy());
        self::assertEquals(1583780978.0000, $auth->getEnergy()->getUpdatedAt());
        self::assertEquals(0, $auth->getEnergy()->getResidue());

        self::assertSameSize($noticesData, $auth->getNotices());

        $i = 0;
        foreach ($auth->getNotices() as $notice) {
            self::assertEquals(
                NoticeFactory::create($noticesData[$i]),
                $notice
            );
            $i++;
        }

        self::assertEquals($level['character_level'], $auth->getLevel()->getLevel());
        self::assertEquals($level['character_stat_points'], $auth->getLevel()->getStatPoints());

        self::assertEquals($avatar, $auth->getAvatar());
    }

    /**
     * Test on fail get Auth at unknown auth token
     *
     * @throws Exception
     */
    public function testAuthRepositoryGetNotFound(): void
    {
        self::assertNull($this->getRepository()->get('unknown_token', $this->getSendNoticeAction()));
    }

    /**
     * @return AuthRepository
     * @throws AppException
     */
    private function getRepository(): AuthRepository
    {
        return new AuthRepository(self::getContainer());
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1',
                self::DEMO_USER,
                'DemoUser',
                1,
                '2dad01e1-af9d-479d-9f48-92823f585827',
                [
                    [
                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477af1',
                        'type'       => 1,
                        'account_id' => self::DEMO_USER,
                        'message'    => 'notice message 1',
                        'view'       => 0,
                        'created_at' => '2021-12-25 11:00:00',
                    ],
                    [

                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477af2',
                        'type'       => 2,
                        'account_id' => self::DEMO_USER,
                        'message'    => 'notice message 2',
                        'view'       => 0,
                        'created_at' => '2021-12-25 12:00:00',
                    ],
                    [

                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477af3',
                        'type'       => 3,
                        'account_id' => self::DEMO_USER,
                        'message'    => 'notice message 3',
                        'view'       => 0,
                        'created_at' => '2021-12-25 13:00:00',
                    ],
                ],
                'level'                  => [
                    'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'character_stat_points' => 0,
                ],
                '/img/avatars/it/analyst/male/01.jpg',
            ],
            [
                'VBajfT8P6PFtrkHhCqb7ZNwIFG45a2',
                self::BLOCKED_USER,
                'BlockedUser',
                2,
                '17746e87-4e15-4c60-8b2f-8cb01032c47a',
                [],
                'level'                  => [
                    'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                    'character_level'       => 2,
                    'character_exp'         => 60,
                    'character_stat_points' => 5,
                ],
                '/img/avatars/it/analyst/female/01.jpg',
            ],
        ];
    }
}
