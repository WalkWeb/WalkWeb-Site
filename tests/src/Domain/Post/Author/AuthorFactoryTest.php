<?php

declare(strict_types=1);

namespace Test\src\Domain\Post\Author;

use App\Domain\Account\AccountException;
use Exception;
use App\Domain\Post\Author\AuthorException;
use App\Domain\Post\Author\AuthorFactory;
use Test\AbstractTest;

class AuthorFactoryTest extends AbstractTest
{
    /**
     * Тест на успешное создание автора поста на основе массива с данными
     *
     * @dataProvider successDataProvider
     * @param array $data
     * @throws Exception
     */
    public function testAuthorFactoryCreateSuccess(array $data): void
    {
        $author = $this->getFactory()->create($data);

        self::assertEquals($data['author_id'], $author->getId());
        self::assertEquals($data['author_name'], $author->getName());
        self::assertEquals($data['author_avatar'], $author->getAvatar());
        self::assertEquals($data['author_level'], $author->getLevel());
        self::assertEquals($data['author_status_id'], $author->getStatus()->getId());
    }

    /**
     * Тест на различные варианты невалидных данных
     *
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     * @throws Exception
     */
    public function testAuthorFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($error);
        $this->getFactory()->create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                ],
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
                // отсутствует author_id
                [
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                ],
                AuthorException::INVALID_ID,
            ],
            [
                // author_id некорректного типа
                [
                    'author_id'        => 10,
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                ],
                AuthorException::INVALID_ID,
            ],
            [
                // отсутствует author_name
                [
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                ],
                AuthorException::INVALID_NAME,
            ],
            [
                // author_name некорректного типа
                [
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => ['Name'],
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                ],
                AuthorException::INVALID_NAME,
            ],
            [
                // отсутствует author_avatar
                [
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                ],
                AuthorException::INVALID_AVATAR,
            ],
            [
                // author_avatar некорректного типа
                [
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => true,
                    'author_level'     => 25,
                    'author_status_id' => 1,
                ],
                AuthorException::INVALID_AVATAR,
            ],
            [
                // отсутствует author_level
                [
                    'author_id'               => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'             => 'Name',
                    'author_avatar'           => 'avatar.png',
                    'author_author_status_id' => 1,
                ],
                AuthorException::INVALID_LEVEL,
            ],
            [
                // author_level некорректного типа
                [
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25.5,
                    'author_status_id' => 1,
                ],
                AuthorException::INVALID_LEVEL,
            ],
            [
                // отсутствует author_status_id
                [
                    'author_id'     => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'   => 'Name',
                    'author_avatar' => 'avatar.png',
                    'author_level'         => 25,
                ],
                AuthorException::INVALID_STATUS_ID,
            ],
            [
                // author_status_id некорректного типа
                [
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => true,
                ],
                AuthorException::INVALID_STATUS_ID,
            ],
            [
                // неизвестный author_status_id
                [
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => $statusId = 333,
                ],
                AccountException::UNKNOWN_STATUS_ID . ': ' . $statusId,
            ],
        ];
    }

    /**
     * @return AuthorFactory
     */
    private function getFactory(): AuthorFactory
    {
        return new AuthorFactory();
    }
}
