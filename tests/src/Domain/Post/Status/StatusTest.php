<?php

declare(strict_types=1);

namespace Test\src\Domain\Post\Status;

use App\Domain\Post\Status\Status;
use App\Domain\Post\Status\StatusException;
use Test\AbstractTest;

class StatusTest extends AbstractTest
{
    /**
     * Тест на успешное создание статуса поста
     *
     * @dataProvider successDataProvider
     * @param int $id
     * @param string $expectedName
     * @throws StatusException
     */
    public function testStatusCreateSuccess(int $id, string $expectedName): void
    {
        $status = new Status($id);

        self::assertEquals($id, $status->getId());
        self::assertEquals($expectedName, $status->getName());
    }

    /**
     * Тест на ошибку создания статуса поста - передан неизвестный статус
     *
     * @dataProvider failDataProvider
     * @param int $id
     * @param string $error
     */
    public function testStatusCreateFail(int $id, string $error): void
    {
        $this->expectException(StatusException::class);
        $this->expectExceptionMessage($error);
        new Status($id);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                1,
                'Default',
            ],
            [
                2,
                'Silver',
            ],
            [
                3,
                'Gold',
            ],
            [
                4,
                'Diamond',
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
                99,
                StatusException::UNKNOWN_POST_STATUS_ID . ': ' . 99,
            ],
        ];
    }
}
