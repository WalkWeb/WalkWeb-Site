<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Collection;

use App\Domain\Account\Collection\AccountListRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class AccountListRepositoryTest extends AbstractTest
{
    /**
     * @dataProvider getAllDataProvider
     * @param int $offset
     * @param int $limit
     * @param array $data
     * @throws AppException
     */
    public function testAccountListRepositoryGetAll(int $offset, int $limit, array $data): void
    {
        $accounts = $this->getRepository()->getAll($offset, $limit);

        self::assertSameSize($data, $accounts);

        $i = 0;
        foreach ($accounts as $account) {
            self::assertEquals($data[$i]['id'], $account->getId());
            self::assertEquals($data[$i]['avatar'], $account->getAvatar());
            self::assertEquals($data[$i]['name'], $account->getName());
            self::assertEquals($data[$i]['level'], $account->getLevel());
            self::assertEquals($data[$i]['exp'], $account->getExp());
            self::assertEquals($data[$i]['status_id'], $account->getStatus()->getId());
            self::assertEquals($data[$i]['group_id'], $account->getGroup()->getId());
            self::assertEquals($data[$i]['carma'], $account->getCarma());
            self::assertEquals($data[$i]['sign'], $account->getCarmaSign());
            self::assertEquals($data[$i]['color'], $account->getCarmaColoClass());
            $i++;
        }
    }

    /**
     * @throws AppException
     */
    public function testAccountListRepositoryGetTotal(): void
    {
        self::assertEquals(11, $this->getRepository()->getTotal());
    }

    /**
     * @return array
     */
    public function getAllDataProvider(): array
    {
        return [
            // check limit
            [
                0,
                2,
                [
                    [
                        'id'        => self::DEMO_USER,
                        'avatar'    => '/img/avatars/it/analyst/male/01s.jpg',
                        'name'      => 'DemoUser',
                        'level'     => 1,
                        'exp'       => 0,
                        'status_id' => 1,
                        'group_id'  => 10,
                        'carma'     => 0,
                        'sign'      => '',
                        'color'     => 'defaultRatingColor',
                    ],
                    [
                        'id'        => self::BLOCKED_USER,
                        'avatar'    => '/img/avatars/it/analyst/female/01s.jpg',
                        'name'      => 'BlockedUser',
                        'level'     => 2,
                        'exp'       => 54,
                        'status_id' => 2,
                        'group_id'  => 10,
                        'carma'     => 3,
                        'sign'      => '+',
                        'color'     => 'positiveRatingColor',
                    ],
                ],
            ],
            // check offset
            [
                2,
                2,
                [
                    [
                        'id'        => self::NO_END_REG_USER,
                        'avatar'    => '/img/avatars/it/designer/male/01s.jpg',
                        'name'      => 'NoEndRegisterUser',
                        'level'     => 3,
                        'exp'       => 150,
                        'status_id' => 1,
                        'group_id'  => 10,
                        'carma'     => 11,
                        'sign'      => '+',
                        'color'     => 'positiveRatingColor',
                    ],
                    [
                        'id'        => self::DEMO_MODERATOR,
                        'avatar'    => '/img/avatars/it/designer/female/01s.jpg',
                        'name'      => 'NameModerator',
                        'level'     => 4,
                        'exp'       => 450,
                        'status_id' => 1,
                        'group_id'  => 20,
                        'carma'     => 43,
                        'sign'      => '+',
                        'color'     => 'positiveRatingColor',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return AccountListRepository
     * @throws AppException
     */
    private function getRepository(): AccountListRepository
    {
        return new AccountListRepository(self::getContainer());
    }
}
