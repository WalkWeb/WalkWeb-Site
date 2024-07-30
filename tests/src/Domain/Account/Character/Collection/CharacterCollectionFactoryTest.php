<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Character\Collection;

use App\Domain\Account\Character\Collection\CharacterCollectionFactory;
use App\Domain\Account\Character\Collection\CharacterListException;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CharacterCollectionFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testCharacterCollectionFactoryCreateSuccess(array $data): void
    {
        $collection = CharacterCollectionFactory::create($data);

        self::assertSameSize($data, $collection);

        $i = 0;
        foreach ($collection as $account) {
            self::assertEquals($data[$i]['id'], $account->getId());
            self::assertEquals($data[$i]['avatar'], $account->getAvatar());
            self::assertEquals($data[$i]['profession'], $account->getProfession());
            self::assertEquals($data[$i]['genesis'], $account->getGenesis());
            self::assertEquals($data[$i]['level'], $account->getLevel());
            $i++;
        }
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     */
    public function testCharacterCollectionFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        CharacterCollectionFactory::create($data);
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
                        'id'         => '185bec4f-8660-4f96-9635-2a261134f0d7',
                        'avatar'     => 'avatar-1.png',
                        'profession' => 'analytics',
                        'genesis'    => 'human',
                        'level'      => 1,
                    ],
                    [
                        'id'         => '185bec4f-8660-4f96-9635-2a261134f123',
                        'avatar'     => 'avatar-2.png',
                        'profession' => 'programmer',
                        'genesis'    => 'human',
                        'level'      => 15,
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
                        'id'         => '185bec4f-8660-4f96-9635-2a261134f0d7',
                        'avatar'     => 'avatar-1.png',
                        'profession' => 'analytics',
                        'genesis'    => 'human',
                        'level'      => 1,
                    ],
                    [
                        'id'         => '185bec4f-8660-4f96-9635-2a261134f0d7',
                        'avatar'     => 'avatar-1.png',
                        'profession' => 'analytics',
                        'genesis'    => 'human',
                        'level'      => 1,
                    ],
                ],
                CharacterListException::ALREADY_EXIST,
            ],
            // data no array
            [
                [
                    [
                        'id'         => '185bec4f-8660-4f96-9635-2a261134f0d7',
                        'avatar'     => 'avatar-1.png',
                        'profession' => 'analytics',
                        'genesis'    => 'human',
                        'level'      => 1,
                    ],
                    123,
                ],
                CharacterListException::EXPECTED_ARRAY,
            ],
        ];
    }
}
