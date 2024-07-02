<?php

declare(strict_types=1);

namespace Tests\src\Domain\Account\Status;

use App\Domain\Account\AccountException;
use App\Domain\Account\Status\AccountStatus;
use Tests\AbstractTest;

class AccountStatusTest extends AbstractTest
{
    /**
     * Test on success created AccountStatus object
     *
     * @dataProvider successDataProvider
     * @param int $id
     * @param string $name
     * @throws AccountException
     */
    public function testAccountStatusCreateSuccess(int $id, string $name): void
    {
        $status = new AccountStatus($id);

        self::assertEquals($id, $status->getId());
        self::assertEquals($name, $status->getName());
    }

    /**
     * Test on fail created AccountStatus object
     *
     * @dataProvider failDataProvider
     * @param int $id
     * @param string $error
     */
    public function testAccountStatusCreateFail(int $id, string $error): void
    {
        $this->expectException(AccountException::class);
        $this->expectExceptionMessage($error);
        new AccountStatus($id);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                1,
                'Active',
            ],
            [
                2,
                'Blocked',
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
                33,
                AccountException::UNKNOWN_STATUS_ID . ': 33',
            ],
        ];
    }
}
