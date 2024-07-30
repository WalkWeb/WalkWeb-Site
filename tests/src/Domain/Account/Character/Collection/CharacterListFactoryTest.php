<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Character\Collection;

use App\Domain\Account\Character\Collection\CharacterListException;
use App\Domain\Account\Character\Collection\CharacterListFactory;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CharacterListFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testCharacterListFactoryCreateSuccess(array $data): void
    {
        $character = CharacterListFactory::create($data);

        self::assertEquals($data['id'], $character->getId());
        self::assertEquals($data['avatar'], $character->getAvatar());
        self::assertEquals($data['profession'], $character->getProfession());
        self::assertEquals($data['genesis'], $character->getGenesis());
        self::assertEquals($data['level'], $character->getLevel());
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     * @throws AppException
     */
    public function testCharacterListFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        CharacterListFactory::create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'id'         => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'     => 'avatar.png',
                    'profession' => 'analytics',
                    'genesis'    => 'human',
                    'level'      => 1,
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
            // miss id
            [
                [
                    'avatar'     => 'avatar.png',
                    'profession' => 'analytics',
                    'genesis'    => 'human',
                    'level'      => 1,
                ],
                CharacterListException::INVALID_ID,
            ],
            // id invalid type
            [
                [
                    'id'         => 100,
                    'avatar'     => 'avatar.png',
                    'profession' => 'analytics',
                    'genesis'    => 'human',
                    'level'      => 1,
                ],
                CharacterListException::INVALID_ID,
            ],
            // miss avatar
            [
                [
                    'id'         => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'profession' => 'analytics',
                    'genesis'    => 'human',
                    'level'      => 1,
                ],
                CharacterListException::INVALID_AVATAR,
            ],
            // avatar invalid type
            [
                [
                    'id'         => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'     => [],
                    'profession' => 'analytics',
                    'genesis'    => 'human',
                    'level'      => 1,
                ],
                CharacterListException::INVALID_AVATAR,
            ],
            // miss profession
            [
                [
                    'id'         => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'     => 'avatar.png',
                    'genesis'    => 'human',
                    'level'      => 1,
                ],
                CharacterListException::INVALID_PROFESSION,
            ],
            // profession invalid type
            [
                [
                    'id'         => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'     => 'avatar.png',
                    'profession' => null,
                    'genesis'    => 'human',
                    'level'      => 1,
                ],
                CharacterListException::INVALID_PROFESSION,
            ],
            // miss genesis
            [
                [
                    'id'         => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'     => 'avatar.png',
                    'profession' => 'analytics',
                    'level'      => 1,
                ],
                CharacterListException::INVALID_GENESIS,
            ],
            // genesis invalid type
            [
                [
                    'id'         => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'     => 'avatar.png',
                    'profession' => 'analytics',
                    'genesis'    => 1.5,
                    'level'      => 1,
                ],
                CharacterListException::INVALID_GENESIS,
            ],
            // miss level
            [
                [
                    'id'         => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'     => 'avatar.png',
                    'profession' => 'analytics',
                    'genesis'    => 'human',
                ],
                CharacterListException::INVALID_LEVEL,
            ],
            // level invalid type
            [
                [
                    'id'         => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'     => 'avatar.png',
                    'profession' => 'analytics',
                    'genesis'    => 'human',
                    'level'      => '1',
                ],
                CharacterListException::INVALID_LEVEL,
            ],
        ];
    }
}
