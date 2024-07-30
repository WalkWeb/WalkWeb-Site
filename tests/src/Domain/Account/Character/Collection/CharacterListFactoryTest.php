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
     * @param string $expectedProfession
     * @throws AppException
     */
    public function testCharacterListFactoryCreateSuccess(array $data, string $expectedProfession): void
    {
        $character = CharacterListFactory::create($data);

        self::assertEquals($data['id'], $character->getId());
        self::assertEquals($data['avatar'], $character->getAvatar());
        self::assertEquals($expectedProfession, $character->getProfession());
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
                    'id'                     => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'                 => 'avatar.png',
                    'profession_name_male'   => 'analytics_male',
                    'profession_name_female' => 'analytics_female',
                    'genesis'                => 'human',
                    'floor_id'               => 1,
                    'level'                  => 1,
                ],
                'analytics_male',
            ],
            [
                [
                    'id'                     => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'                 => 'avatar.png',
                    'profession_name_male'   => 'analytics_male',
                    'profession_name_female' => 'analytics_female',
                    'genesis'                => 'human',
                    'floor_id'               => 2,
                    'level'                  => 1,
                ],
                'analytics_female',
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
                    'avatar'                 => 'avatar.png',
                    'profession_name_male'   => 'analytics_male',
                    'profession_name_female' => 'analytics_female',
                    'genesis'                => 'human',
                    'floor_id'               => 1,
                    'level'                  => 1,
                ],
                CharacterListException::INVALID_ID,
            ],
            // id invalid type
            [
                [
                    'id'                     => 100,
                    'avatar'                 => 'avatar.png',
                    'profession_name_male'   => 'analytics_male',
                    'profession_name_female' => 'analytics_female',
                    'genesis'                => 'human',
                    'floor_id'               => 1,
                    'level'                  => 1,
                ],
                CharacterListException::INVALID_ID,
            ],
            // miss avatar
            [
                [
                    'id'                     => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'profession_name_male'   => 'analytics_male',
                    'profession_name_female' => 'analytics_female',
                    'genesis'                => 'human',
                    'floor_id'               => 1,
                    'level'                  => 1,
                ],
                CharacterListException::INVALID_AVATAR,
            ],
            // avatar invalid type
            [
                [
                    'id'                     => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'                 => [],
                    'profession_name_male'   => 'analytics_male',
                    'profession_name_female' => 'analytics_female',
                    'genesis'                => 'human',
                    'floor_id'               => 1,
                    'level'                  => 1,
                ],
                CharacterListException::INVALID_AVATAR,
            ],
            // miss profession_name_male
            [
                [
                    'id'                     => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'                 => 'avatar.png',
                    'profession_name_female' => 'analytics_female',
                    'genesis'                => 'human',
                    'floor_id'               => 1,
                    'level'                  => 1,
                ],
                CharacterListException::INVALID_PROFESSION_MALE,
            ],
            // profession_name_male invalid type
            [
                [
                    'id'                     => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'                 => 'avatar.png',
                    'profession_name_male'   => null,
                    'profession_name_female' => 'analytics_female',
                    'genesis'                => 'human',
                    'floor_id'               => 1,
                    'level'                  => 1,
                ],
                CharacterListException::INVALID_PROFESSION_MALE,
            ],
            // miss profession_name_female
            [
                [
                    'id'                     => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'                 => 'avatar.png',
                    'profession_name_male'   => 'analytics_male',
                    'genesis'                => 'human',
                    'floor_id'               => 1,
                    'level'                  => 1,
                ],
                CharacterListException::INVALID_PROFESSION_FEMALE,
            ],
            // profession_name_female invalid type
            [
                [
                    'id'                     => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'                 => 'avatar.png',
                    'profession_name_male'   => 'analytics_male',
                    'profession_name_female' => false,
                    'genesis'                => 'human',
                    'floor_id'               => 1,
                    'level'                  => 1,
                ],
                CharacterListException::INVALID_PROFESSION_FEMALE,
            ],
            // miss genesis
            [
                [
                    'id'                     => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'                 => 'avatar.png',
                    'profession_name_male'   => 'analytics_male',
                    'profession_name_female' => 'analytics_female',
                    'floor_id'               => 1,
                    'level'                  => 1,
                ],
                CharacterListException::INVALID_GENESIS,
            ],
            // genesis invalid type
            [
                [
                    'id'                     => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'                 => 'avatar.png',
                    'profession_name_male'   => 'analytics_male',
                    'profession_name_female' => 'analytics_female',
                    'genesis'                => 1.5,
                    'floor_id'               => 1,
                    'level'                  => 1,
                ],
                CharacterListException::INVALID_GENESIS,
            ],
            // miss floor_id
            [
                [
                    'id'                     => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'                 => 'avatar.png',
                    'profession_name_male'   => 'analytics_male',
                    'profession_name_female' => 'analytics_female',
                    'genesis'                => 'human',
                    'level'                  => 1,
                ],
                CharacterListException::INVALID_FLOOR_ID,
            ],
            // floor_id invalid type
            [
                [
                    'id'                     => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'                 => 'avatar.png',
                    'profession_name_male'   => 'analytics_male',
                    'profession_name_female' => 'analytics_female',
                    'genesis'                => 'human',
                    'floor_id'               => true,
                    'level'                  => 1,
                ],
                CharacterListException::INVALID_FLOOR_ID,
            ],
            // miss level
            [
                [
                    'id'                     => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'                 => 'avatar.png',
                    'profession_name_male'   => 'analytics_male',
                    'profession_name_female' => 'analytics_female',
                    'genesis'                => 'human',
                    'floor_id'               => 1,
                ],
                CharacterListException::INVALID_LEVEL,
            ],
            // level invalid type
            [
                [
                    'id'                     => '185bec4f-8660-4f96-9635-2a261134f0d7',
                    'avatar'                 => 'avatar.png',
                    'profession_name_male'   => 'analytics_male',
                    'profession_name_female' => 'analytics_female',
                    'genesis'                => 'human',
                    'floor_id'               => 1,
                    'level'                  => '1',
                ],
                CharacterListException::INVALID_LEVEL,
            ],
        ];
    }
}
