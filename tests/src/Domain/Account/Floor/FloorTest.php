<?php

declare(strict_types=1);

namespace Tests\src\Domain\Account\Floor;

use App\Domain\Account\AccountException;
use App\Domain\Account\Floor\Floor;
use Tests\AbstractTest;

class FloorTest extends AbstractTest
{
    /**
     * Test on success created Floor object
     *
     * @dataProvider successDataProvider
     * @param int $id
     * @param string $name
     * @throws AccountException
     */
    public function testAccountGroupCreateSuccess(int $id, string $name): void
    {
        $status = new Floor($id);

        self::assertEquals($id, $status->getId());
        self::assertEquals($name, $status->getName());
    }

    /**
     * Test on fail created Floor object
     *
     * @dataProvider failDataProvider
     * @param int $id
     * @param string $error
     */
    public function testAccountGroupCreateFail(int $id, string $error): void
    {
        $this->expectException(AccountException::class);
        $this->expectExceptionMessage($error);
        new Floor($id);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                1,
                'Male',
            ],
            [
                2,
                'Female',
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
                3,
                AccountException::UNKNOWN_FLOOR_ID . ': 3',
            ],
        ];
    }
}
