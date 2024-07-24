<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Character\Level;

use App\Domain\Account\Character\Level\LevelFactory;
use App\Domain\Account\Character\Level\LevelInterface;
use App\Domain\Account\MainCharacter\Level\LevelException;
use Exception;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class LevelFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testLevelFactoryCreateSuccess(array $data): void
    {
        $level = LevelFactory::create($data);

        self::assertEquals($data['account_id'], $level->getAccountId());
        self::assertEquals($data['main_character_id'], $level->getMainCharacterId());
        self::assertEquals($data['character_id'], $level->getCharacterId());
        self::assertEquals($data['character_level'], $level->getLevel());
        self::assertEquals($data['character_exp'], $level->getExp());
        self::assertEquals($data['character_stat_points'], $level->getStatPoints());
        self::assertEquals($data['character_skill_points'], $level->getSkillPoints());
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     * @throws AppException
     */
    public function testLevelFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        LevelFactory::create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
            ],
            [
                [
                    'account_id'             => 'b0fe5a38-a8ec-449c-badc-ebc2f3962312',
                    'main_character_id'      => 'd9f6300a-2ed6-4099-be01-e7ff39d8d3bc',
                    'character_id'           => '10756e76-a27d-4bfd-b392-50c1635b822f',
                    'character_level'        => 1,
                    'character_exp'          => 0,
                    'character_stat_points'  => 0,
                    'character_skill_points' => 0,
                ],
            ],
        ];
    }

    /**
     * @return array[]
     * @throws Exception
     */
    public function failDataProvider(): array
    {
        return [
            // miss account_id
            [
                [
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_ACCOUNT_ID_DATA,
            ],
            // account_id invalid type
            [
                [
                    'account_id'             => 1,
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_ACCOUNT_ID_DATA,
            ],
            // account_id over min length
            [
                [
                    'account_id'             => self::generateString(LevelInterface::ACCOUNT_ID_MIN_LENGTH - 1),
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_ACCOUNT_ID_VALUE . LevelInterface::ACCOUNT_ID_MIN_LENGTH . '-' . LevelInterface::ACCOUNT_ID_MAX_LENGTH,
            ],
            // account_id over max length
            [
                [
                    'account_id'             => self::generateString(LevelInterface::ACCOUNT_ID_MAX_LENGTH + 1),
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_ACCOUNT_ID_VALUE . LevelInterface::ACCOUNT_ID_MIN_LENGTH . '-' . LevelInterface::ACCOUNT_ID_MAX_LENGTH,
            ],
            // miss main_character_id
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_MAIN_CHARACTER_ID_DATA,
            ],
            // main_character_id invalid type
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => null,
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_MAIN_CHARACTER_ID_DATA,
            ],
            // main_character_id over min length
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => self::generateString(LevelInterface::CHARACTER_ID_MIN_LENGTH - 1),
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_MAIN_CHARACTER_ID_VALUE . LevelInterface::CHARACTER_ID_MIN_LENGTH . '-' . LevelInterface::CHARACTER_ID_MAX_LENGTH,
            ],
            // main_character_id over max length
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => self::generateString(LevelInterface::CHARACTER_ID_MAX_LENGTH + 1),
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_MAIN_CHARACTER_ID_VALUE . LevelInterface::CHARACTER_ID_MIN_LENGTH . '-' . LevelInterface::CHARACTER_ID_MAX_LENGTH,
            ],
            // miss character_id
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_CHARACTER_ID_DATA,
            ],
            // character_id invalid type
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => [],
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_CHARACTER_ID_DATA,
            ],
            // character_id over min length
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => self::generateString(LevelInterface::CHARACTER_ID_MIN_LENGTH - 1),
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_CHARACTER_ID_VALUE . LevelInterface::CHARACTER_ID_MIN_LENGTH . '-' . LevelInterface::CHARACTER_ID_MAX_LENGTH,
            ],
            // character_id over max length
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => self::generateString(LevelInterface::CHARACTER_ID_MAX_LENGTH + 1),
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_CHARACTER_ID_VALUE . LevelInterface::CHARACTER_ID_MIN_LENGTH . '-' . LevelInterface::CHARACTER_ID_MAX_LENGTH,
            ],
            // miss character_level
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_LEVEL_DATA,
            ],
            // character_level invalid type
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => '2',
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_LEVEL_DATA,
            ],
            // character_level over min value
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => LevelInterface::MIN_LEVEL - 1,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_LEVEL_VALUE . LevelInterface::MIN_LEVEL . '-' . LevelInterface::MAX_LEVEL,
            ],
            // character_level over max value
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => LevelInterface::MAX_LEVEL + 1,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_LEVEL_VALUE . LevelInterface::MIN_LEVEL . '-' . LevelInterface::MAX_LEVEL,
            ],
            // miss character_exp
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_EXP_DATA,
            ],
            // character_exp invalid type
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => [],
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_EXP_DATA,
            ],
            // character_exp over min value
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => LevelInterface::MIN_EXP - 1,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_EXP_VALUE . LevelInterface::MIN_EXP . '-' . LevelInterface::MAX_EXP,
            ],
            // character_exp over max value
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => LevelInterface::MAX_EXP + 1,
                    'character_stat_points'  => 5,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_EXP_VALUE . LevelInterface::MIN_EXP . '-' . LevelInterface::MAX_EXP,
            ],
            // miss character_stat_points
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_STAT_POINTS_DATA,
            ],
            // character_stat_points invalid type
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => null,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_STAT_POINTS_DATA,
            ],
            // character_stat_points over min value
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => LevelInterface::MIN_STAT_POINTS - 1,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_STAT_POINTS_VALUE . LevelInterface::MIN_STAT_POINTS . '-' . LevelInterface::MAX_STAT_POINTS,
            ],
            // character_stat_points over max value
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => LevelInterface::MAX_STAT_POINTS + 1,
                    'character_skill_points' => 1,
                ],
                LevelException::INVALID_STAT_POINTS_VALUE . LevelInterface::MIN_STAT_POINTS . '-' . LevelInterface::MAX_STAT_POINTS,
            ],
            // miss character_skill_points
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                ],
                LevelException::INVALID_SKILL_POINTS_DATA,
            ],
            // character_skill_points invalid type
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => null,
                ],
                LevelException::INVALID_SKILL_POINTS_DATA,
            ],
            // character_skill_points over min value
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => LevelInterface::MIN_SKILL_POINTS - 1,
                ],
                LevelException::INVALID_SKILL_POINTS_VALUE . LevelInterface::MIN_SKILL_POINTS . '-' . LevelInterface::MAX_SKILL_POINTS,
            ],
            // character_skill_points over max value
            [
                [
                    'account_id'             => 'c405ddd4-f11a-4607-a22a-8fe858f8b0bd',
                    'main_character_id'      => 'e8a08ed9-3ecc-4a88-8239-25eeaf162a04',
                    'character_id'           => '993e9277-b1bc-405c-907d-75c12802d556',
                    'character_level'        => 2,
                    'character_exp'          => 214,
                    'character_stat_points'  => 5,
                    'character_skill_points' => LevelInterface::MAX_SKILL_POINTS + 1,
                ],
                LevelException::INVALID_SKILL_POINTS_VALUE . LevelInterface::MIN_SKILL_POINTS . '-' . LevelInterface::MAX_SKILL_POINTS,
            ],
        ];
    }
}
