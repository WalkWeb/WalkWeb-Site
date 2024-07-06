<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\MainCharacter;

use App\Domain\Account\MainCharacter\MainCharacterException;
use App\Domain\Account\MainCharacter\MainCharacterFactory;
use App\Domain\Account\Notice\Action\SendNoticeAction;
use App\Domain\Account\Notice\Action\SendNoticeActionInterface;
use App\Domain\Account\Notice\NoticeRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class MainCharacterFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testMainCharacterFactoryCreateSuccess(array $data): void
    {
        $character = MainCharacterFactory::create($data, $this->getSendNoticeAction());

        self::assertEquals($data['character_id'], $character->getId());
        self::assertEquals($data['account_id'], $character->getAccountId());
        self::assertEquals($data['era_id'], $character->getEra()->getId());
        self::assertEquals($data['character_level'], $character->getLevel()->getLevel());
        self::assertEquals($data['character_exp'], $character->getLevel()->getExp());
        self::assertEquals($data['energy_bonus'], $character->getEnergyBonus());
        self::assertEquals($data['upload_bonus'], $character->getUploadBonus());
        self::assertEquals($data['character_stat_points'], $character->getLevel()->getStatPoints());
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     */
    public function testMainCharacterFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        MainCharacterFactory::create($data, $this->getSendNoticeAction());
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'character_id'          => 'e7c3effa-1dbf-42af-a105-17eb06c7a6e0',
                    'account_id'            => '2bb441e6-ea08-4a69-991b-20deadae7406',
                    'era_id'                => 1,
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'energy_bonus'          => 0,
                    'upload_bonus'          => 0,
                    'character_stat_points' => 0,
                ],
            ],
        ];
    }

    public function failDataProvider(): array
    {
        return [
            // miss character_id
            [
                [
                    'account_id'            => '2bb441e6-ea08-4a69-991b-20deadae7406',
                    'era_id'                => 1,
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'energy_bonus'          => 0,
                    'upload_bonus'          => 0,
                    'character_stat_points' => 0,
                ],
                MainCharacterException::INVALID_ID,
            ],
            // character_id invalid type
            [
                [
                    'character_id'          => 123,
                    'account_id'            => '2bb441e6-ea08-4a69-991b-20deadae7406',
                    'era_id'                => 1,
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'energy_bonus'          => 0,
                    'upload_bonus'          => 0,
                    'character_stat_points' => 0,
                ],
                MainCharacterException::INVALID_ID,
            ],
            // character_id invalid uuid
            [
                [
                    'character_id'          => '2bb441e6-ea08-4a69-991b-20deadae740_',
                    'account_id'            => '2bb441e6-ea08-4a69-991b-20deadae7406',
                    'era_id'                => 1,
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'energy_bonus'          => 0,
                    'upload_bonus'          => 0,
                    'character_stat_points' => 0,
                ],
                MainCharacterException::INVALID_ID_VALUE,
            ],

            // miss account_id
            [
                [
                    'character_id'          => 'e7c3effa-1dbf-42af-a105-17eb06c7a6e0',
                    'era_id'                => 1,
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'energy_bonus'          => 0,
                    'upload_bonus'          => 0,
                    'character_stat_points' => 0,
                ],
                MainCharacterException::INVALID_ACCOUNT_ID,
            ],
            // miss account_id
            [
                [
                    'character_id'          => 'e7c3effa-1dbf-42af-a105-17eb06c7a6e0',
                    'account_id'            => null,
                    'era_id'                => 1,
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'energy_bonus'          => 0,
                    'upload_bonus'          => 0,
                    'character_stat_points' => 0,
                ],
                MainCharacterException::INVALID_ACCOUNT_ID,
            ],
            // account_id invalid uuid
            [
                [
                    'character_id'          => 'e7c3effa-1dbf-42af-a105-17eb06c7a6e0',
                    'account_id'            => 'e7c3effa-1dbf-42af-a105-17eb06c7axxx',
                    'era_id'                => 1,
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'energy_bonus'          => 0,
                    'upload_bonus'          => 0,
                    'character_stat_points' => 0,
                ],
                MainCharacterException::INVALID_ACCOUNT_ID_VALUE,
            ],
            // miss era_id
            [
                [
                    'character_id'          => 'e7c3effa-1dbf-42af-a105-17eb06c7a6e0',
                    'account_id'            => '2bb441e6-ea08-4a69-991b-20deadae7406',
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'energy_bonus'          => 0,
                    'upload_bonus'          => 0,
                    'character_stat_points' => 0,
                ],
                MainCharacterException::INVALID_ERA_ID,
            ],
            // era_id invalid type
            [
                [
                    'character_id'          => 'e7c3effa-1dbf-42af-a105-17eb06c7a6e0',
                    'account_id'            => '2bb441e6-ea08-4a69-991b-20deadae7406',
                    'era_id'                => false,
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'energy_bonus'          => 0,
                    'upload_bonus'          => 0,
                    'character_stat_points' => 0,
                ],
                MainCharacterException::INVALID_ERA_ID,
            ],

            // miss energy_bonus
            [
                [
                    'character_id'          => 'e7c3effa-1dbf-42af-a105-17eb06c7a6e0',
                    'account_id'            => '2bb441e6-ea08-4a69-991b-20deadae7406',
                    'era_id'                => 1,
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'upload_bonus'          => 0,
                    'character_stat_points' => 0,
                ],
                MainCharacterException::INVALID_ENERGY_BONUS,
            ],
            // energy_bonus invalid type
            [
                [
                    'character_id'          => 'e7c3effa-1dbf-42af-a105-17eb06c7a6e0',
                    'account_id'            => '2bb441e6-ea08-4a69-991b-20deadae7406',
                    'era_id'                => 1,
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'energy_bonus'          => [],
                    'upload_bonus'          => 0,
                    'character_stat_points' => 0,
                ],
                MainCharacterException::INVALID_ENERGY_BONUS,
            ],

            // miss upload_bonus
            [
                [
                    'character_id'          => 'e7c3effa-1dbf-42af-a105-17eb06c7a6e0',
                    'account_id'            => '2bb441e6-ea08-4a69-991b-20deadae7406',
                    'era_id'                => 1,
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'energy_bonus'          => 0,
                    'character_stat_points' => 0,
                ],
                MainCharacterException::INVALID_UPLOAD_BONUS,
            ],
            // upload_bonus invalid type
            [
                [
                    'character_id'          => 'e7c3effa-1dbf-42af-a105-17eb06c7a6e0',
                    'account_id'            => '2bb441e6-ea08-4a69-991b-20deadae7406',
                    'era_id'                => 1,
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'energy_bonus'          => 0,
                    'upload_bonus'          => '0',
                    'character_stat_points' => 0,
                ],
                MainCharacterException::INVALID_UPLOAD_BONUS,
            ],
        ];
    }

    /**
     * @return SendNoticeActionInterface
     * @throws AppException
     */
    private function getSendNoticeAction(): SendNoticeActionInterface
    {
        return new SendNoticeAction(new NoticeRepository(self::getContainer()));
    }
}
