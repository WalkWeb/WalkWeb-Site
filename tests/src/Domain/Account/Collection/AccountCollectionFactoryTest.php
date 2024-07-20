<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Collection;

use App\Domain\Account\AccountException;
use App\Domain\Account\Collection\AccountCollectionFactory;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class AccountCollectionFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AccountException
     * @throws AppException
     */
    public function testAccountCollectionFactorySuccess(array $data): void
    {
        $collection = AccountCollectionFactory::create($data);

        self::assertSameSize($data, $collection);

        $i = 0;
        foreach ($collection as $account) {
            self::assertEquals($data[$i]['id'], $account->getId());
            self::assertEquals($data[$i]['avatar'], $account->getAvatar());
            self::assertEquals($data[$i]['name'], $account->getName());
            self::assertEquals($data[$i]['level'], $account->getLevel());
            self::assertEquals($data[$i]['exp'], $account->getExp());
            self::assertEquals($data[$i]['status_id'], $account->getStatus()->getId());
            self::assertEquals($data[$i]['group_id'], $account->getGroup()->getId());
            self::assertEquals($data[$i]['carma'], $account->getCarma());
            $i++;
        }
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     * @throws AccountException
     * @throws AppException
     */
    public function testAccountCollectionFactoryFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        AccountCollectionFactory::create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    [
                        'id'        => 'ea5885e8-242b-4953-bb0f-7e2b86c318d1',
                        'avatar'    => 'avatar-1.png',
                        'name'      => 'name-1',
                        'level'     => 3,
                        'exp'       => 275,
                        'status_id' => 1,
                        'group_id'  => 10,
                        'carma'     => 10,
                    ],
                    [
                        'id'        => 'ea5885e8-242b-4953-bb0f-7e2b86c318d2',
                        'avatar'    => 'avatar-2.png',
                        'name'      => 'name-2',
                        'level'     => 5,
                        'exp'       => 1275,
                        'status_id' => 2,
                        'group_id'  => 20,
                        'carma'     => -32,
                    ],
                    [
                        'id'        => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                        'avatar'    => 'avatar-3.png',
                        'name'      => 'name-3',
                        'level'     => 1,
                        'exp'       => 12,
                        'status_id' => 1,
                        'group_id'  => 10,
                        'carma'     => 21,
                    ],
                ],
            ],
        ];
    }

    public function failDataProvider(): array
    {
        return [
            // already exist
            [
                [
                    [
                        'id'        => 'ea5885e8-242b-4953-bb0f-7e2b86c318d1',
                        'avatar'    => 'avatar-1.png',
                        'name'      => 'name-1',
                        'level'     => 3,
                        'exp'       => 275,
                        'status_id' => 1,
                        'group_id'  => 10,
                        'carma'     => 10,
                    ],
                    [
                        'id'        => 'ea5885e8-242b-4953-bb0f-7e2b86c318d1',
                        'avatar'    => 'avatar-2.png',
                        'name'      => 'name-2',
                        'level'     => 5,
                        'exp'       => 1275,
                        'status_id' => 2,
                        'group_id'  => 20,
                        'carma'     => -32,
                    ],
                ],
                AccountException::ALREADY_EXIST,
            ],
            // data no array
            [
                [
                    [
                        'id'        => 'ea5885e8-242b-4953-bb0f-7e2b86c318d1',
                        'avatar'    => 'avatar-1.png',
                        'name'      => 'name-1',
                        'level'     => 3,
                        'exp'       => 275,
                        'status_id' => 1,
                        'group_id'  => 10,
                        'carma'     => 10,
                    ],
                    'string',
                ],
                AccountException::EXPECTED_ARRAY,
            ],
        ];
    }
}
