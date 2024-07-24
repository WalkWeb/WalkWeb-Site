<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\MainCharacter\Level;

use App\Domain\Account\MainCharacter\Level\LevelException;
use App\Domain\Account\MainCharacter\Level\LevelFactory;
use App\Domain\Account\MainCharacter\Level\LevelInterface;
use Exception;
use Test\AbstractTest;

class MainLevelFactoryTest extends AbstractTest
{
    /**
     * Тест на успешное создание объекта Level на основе массива параметров
     *
     * @dataProvider successDataProvider
     * @param array $data
     * @param int $expectedExpToLevel
     * @param int $expectedExpAtLevel
     * @param int $expectedExpBarWeight
     * @throws Exception
     */
    public function testLevelFactoryCreateSuccess(
        array $data,
        int $expectedExpToLevel,
        int $expectedExpAtLevel,
        int $expectedExpBarWeight
    ): void
    {
        $level = LevelFactory::create($data, $this->getSendNoticeAction());

        self::assertEquals($data['account_id'], $level->getAccountId());
        self::assertEquals($data['character_id'], $level->getCharacterId());
        self::assertEquals($data['character_level'], $level->getLevel());
        self::assertEquals($data['character_exp'], $level->getExp());
        self::assertEquals($data['character_stat_points'], $level->getStatPoints());

        self::assertEquals($expectedExpToLevel, $level->getExpToLevel());
        self::assertEquals($expectedExpAtLevel, $level->getExpAtLevel());
        self::assertEquals($expectedExpBarWeight, $level->getExpBarWeight());
    }

    /**
     * Тест на различные варианты невалидных данных
     *
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     * @throws Exception
     */
    public function testLevelFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($error);
        LevelFactory::create($data, $this->getSendNoticeAction());
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'character_stat_points' => 0,
                ],
                50,
                0,
                0,
            ],
            [
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level'       => 1,
                    'character_exp'         => 25,
                    'character_stat_points' => 3,
                ],
                50,
                25,
                50,
            ],
            [
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level'       => 45,
                    'character_exp'         => 296400,
                    'character_stat_points' => 0,
                ],
                17600,
                200,
                1,
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
            // account_id
            [
                // Отсутствует account_id
                [
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level'       => 1,
                    'character_exp'         => 25,
                    'character_stat_points' => 3,
                ],
                LevelException::INVALID_ACCOUNT_ID_DATA,
            ],
            [
                // account_id некорректного типа
                [
                    'account_id'            => 10,
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level'       => 1,
                    'character_exp'         => 25,
                    'character_stat_points' => 3,
                ],
                LevelException::INVALID_ACCOUNT_ID_DATA,
            ],
            [
                // account_id меньше минимальной длины
                [
                    'account_id'            => self::generateString(LevelInterface::ACCOUNT_ID_MIN_LENGTH - 1),
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level'       => 1,
                    'character_exp'         => 25,
                    'character_stat_points' => 3,
                ],
                LevelException::INVALID_ACCOUNT_ID_VALUE . LevelInterface::ACCOUNT_ID_MIN_LENGTH . '-' . LevelInterface::ACCOUNT_ID_MAX_LENGTH,
            ],
            [
                // account_id больше максимальной длины
                [
                    'account_id'            => self::generateString(LevelInterface::ACCOUNT_ID_MAX_LENGTH + 1),
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level'       => 1,
                    'character_exp'         => 25,
                    'character_stat_points' => 3,
                ],
                LevelException::INVALID_ACCOUNT_ID_VALUE . LevelInterface::ACCOUNT_ID_MIN_LENGTH . '-' . LevelInterface::ACCOUNT_ID_MAX_LENGTH,
            ],
            // character_id
            [
                // Отсутствует character_id
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_level'       => 1,
                    'character_exp'         => 25,
                    'character_stat_points' => 3,
                ],
                LevelException::INVALID_CHARACTER_ID_DATA,
            ],
            [
                // character_id некорректного типа
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => 100,
                    'character_level'       => 1,
                    'character_exp'         => 25,
                    'character_stat_points' => 3,
                ],
                LevelException::INVALID_CHARACTER_ID_DATA,
            ],
            [
                // character_id меньше минимальной длины
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => self::generateString(LevelInterface::CHARACTER_ID_MIN_LENGTH - 1),
                    'character_level'       => 1,
                    'character_exp'         => 25,
                    'character_stat_points' => 3,
                ],
                LevelException::INVALID_CHARACTER_ID_VALUE . LevelInterface::ACCOUNT_ID_MIN_LENGTH . '-' . LevelInterface::ACCOUNT_ID_MAX_LENGTH,
            ],
            [
                // character_id больше максимальной длины
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => self::generateString(LevelInterface::CHARACTER_ID_MAX_LENGTH + 1),
                    'character_level'       => 1,
                    'character_exp'         => 25,
                    'character_stat_points' => 3,
                ],
                LevelException::INVALID_CHARACTER_ID_VALUE . LevelInterface::CHARACTER_ID_MIN_LENGTH . '-' . LevelInterface::CHARACTER_ID_MAX_LENGTH,
            ],
            // character_level
            [
                // Отсутствует character_level
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_exp'         => 0,
                    'character_stat_points' => 0,
                ],
                LevelException::INVALID_LEVEL_DATA,
            ],
            [
                // character_level некорректного типа
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level'       => '1',
                    'character_exp'         => 0,
                    'character_stat_points' => 0,
                ],
                LevelException::INVALID_LEVEL_DATA,
            ],
            [
                // character_level меньше минимального значения
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level'       => LevelInterface::MIN_LEVEL - 1,
                    'character_exp'         => 0,
                    'character_stat_points' => 0,
                ],
                LevelException::INVALID_LEVEL_VALUE . LevelInterface::MIN_LEVEL . '-' . LevelInterface::MAX_LEVEL,
            ],
            [
                // character_level больше максимального значения
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level'       => LevelInterface::MAX_LEVEL + 1,
                    'character_exp'         => 0,
                    'character_stat_points' => 0,
                ],
                LevelException::INVALID_LEVEL_VALUE . LevelInterface::MIN_LEVEL . '-' . LevelInterface::MAX_LEVEL,
            ],

            // character_exp
            [
                // Отсутствует character_exp
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level'       => 1,
                    'character_stat_points' => 0,
                ],
                LevelException::INVALID_EXP_DATA,
            ],
            [
                // character_exp некорректного типа
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level'       => 1,
                    'character_exp'         => null,
                    'character_stat_points' => 0,
                ],
                LevelException::INVALID_EXP_DATA,
            ],
            [
                // character_exp меньше минимального значения
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level'       => 1,
                    'character_exp'         => LevelInterface::MIN_EXP - 1,
                    'character_stat_points' => 0,
                ],
                LevelException::INVALID_EXP_VALUE . LevelInterface::MIN_EXP . '-' . LevelInterface::MAX_EXP,
            ],
            [
                // character_exp больше максимального значения
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level'       => 1,
                    'character_exp'         => LevelInterface::MAX_EXP + 1,
                    'character_stat_points' => 0,
                ],
                LevelException::INVALID_EXP_VALUE . LevelInterface::MIN_EXP . '-' . LevelInterface::MAX_EXP,
            ],

            // character_stat_points
            [
                // Отсутствует character_stat_points
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level' => 1,
                    'character_exp'   => 0,
                ],
                LevelException::INVALID_STAT_POINTS_DATA,
            ],
            [
                // character_stat_points некорректного типа
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'character_stat_points' => [0],
                ],
                LevelException::INVALID_STAT_POINTS_DATA,
            ],
            [
                // character_stat_points меньше минимального значения
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'character_stat_points' => LevelInterface::MIN_STAT_POINTS - 1,
                ],
                LevelException::INVALID_STAT_POINTS_VALUE . LevelInterface::MIN_STAT_POINTS . '-' . LevelInterface::MAX_STAT_POINTS,
            ],
            [
                // character_stat_points больше максимального значения
                [
                    'account_id'            => 'cafc3584-74ea-4fba-bbc3-205bde3697d0',
                    'character_id'          => '2f3de667-d5a4-48c8-bcbf-b9a2b3257719',
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'character_stat_points' => LevelInterface::MAX_STAT_POINTS + 1,
                ],
                LevelException::INVALID_STAT_POINTS_VALUE . LevelInterface::MIN_STAT_POINTS . '-' . LevelInterface::MAX_STAT_POINTS,
            ],
        ];
    }
}
