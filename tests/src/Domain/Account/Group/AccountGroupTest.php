<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Group;

use App\Domain\Account\AccountException;
use App\Domain\Account\Group\AccountGroup;
use Test\AbstractTest;

class AccountGroupTest extends AbstractTest
{
    /**
     * Test on success created AccountGroup object
     *
     * @dataProvider successDataProvider
     * @param int $id
     * @param string $name
     * @throws AccountException
     */
    public function testAccountGroupCreateSuccess(int $id, string $name): void
    {
        $status = new AccountGroup($id);

        self::assertEquals($id, $status->getId());
        self::assertEquals($name, $status->getName());
    }

    /**
     * Test on fail created AccountGroup object
     *
     * @dataProvider failDataProvider
     * @param int $id
     * @param string $error
     */
    public function testAccountGroupCreateFail(int $id, string $error): void
    {
        $this->expectException(AccountException::class);
        $this->expectExceptionMessage($error);
        new AccountGroup($id);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                10,
                'User',
            ],
            [
                20,
                'Moderator',
            ],
            [
                31,
                'Admin',
            ],
            [
                30,
                'Main Admin',
            ],
        ];
    }

    /**
     * @return array
     */
    public function failDataProvider(): array
    {
        return [
            [
                77,
                AccountException::UNKNOWN_GROUP_ID . ': 77',
            ],
        ];
    }
}
