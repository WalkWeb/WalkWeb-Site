<?php

declare(strict_types=1);

namespace Test\src\Domain\Post\Status;

use App\Domain\Post\Status\PostStatus;
use App\Domain\Post\Status\StatusException;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class StatusTest extends AbstractTest
{
    /**
     * Тест на успешное создание статуса поста
     *
     * @dataProvider successDataProvider
     * @param int $id
     * @param string $expectedName
     * @throws AppException
     */
    public function testStatusCreateSuccess(int $id, string $expectedName): void
    {
        $status = new PostStatus($id);

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
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        new PostStatus($id);
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
