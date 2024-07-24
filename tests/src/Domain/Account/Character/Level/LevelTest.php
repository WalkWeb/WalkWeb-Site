<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Character\Level;

use App\Domain\Account\Character\Level\Level;
use App\Domain\Account\MainCharacter\Level\LevelException;
use App\Domain\Account\Character\Level\LevelInterface;
use Exception;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class LevelTest extends AbstractTest
{
    /**
     * Тест на создание объекта Level
     *
     * @dataProvider createDataProvider
     * @param string $accountId
     * @param string $mainCharacterId
     * @param string $characterId
     * @param int $levelValue
     * @param int $exp
     * @param int $statPoints
     * @param int $skillPoints
     * @param int $expectedExpToLevel
     * @param int $expectedExpAtLevel
     * @param int $expectedExpBarWeight
     * @throws AppException
     */
    public function testLevelCreate(
        string $accountId,
        string $mainCharacterId,
        string $characterId,
        int $levelValue,
        int $exp,
        int $statPoints,
        int $skillPoints,
        int $expectedExpToLevel,
        int $expectedExpAtLevel,
        int $expectedExpBarWeight
    ): void
    {
        $level = new Level($accountId, $mainCharacterId, $characterId, $levelValue, $exp, $statPoints, $skillPoints);

        // Параметры, которые указываются напрямую
        self::assertEquals($accountId, $level->getAccountId());
        self::assertEquals($mainCharacterId, $level->getMainCharacterId());
        self::assertEquals($characterId, $level->getCharacterId());
        self::assertEquals($levelValue, $level->getLevel());
        self::assertEquals($exp, $level->getExp());
        self::assertEquals($statPoints, $level->getStatPoints());
        self::assertEquals($skillPoints, $level->getSkillPoints());

        // Параметры, которые рассчитываются на основе уровня и опыта на основе внутренних данных по уровням
        self::assertEquals($expectedExpToLevel, $level->getExpToLevel());
        self::assertEquals($expectedExpAtLevel, $level->getExpAtLevel());

        // Параметр, который рассчитывается на ходу
        self::assertEquals($expectedExpBarWeight, $level->getExpBarWeight());
    }

    /**
     * Тест на ситуацию, когда указан некорректный (отсутствующий в данных внутри класса Level) уровень
     *
     * @throws Exception
     */
    public function testLevelInvalid(): void
    {
        $level = 200;

        $this->expectException(AppException::class);
        $this->expectExceptionMessage(LevelException::INVALID_LEVEL . ': ' . $level);
        new Level(
            '3544c1bc-8757-47db-9998-6f14522b5252',
            '2bfce8f5-9099-4ca0-afe5-bf9ceae9a922',
            '556d9249-5f5f-47d4-b41c-ca580f6c5e23',
            $level,
            500,
            10,
            4
        );
    }

    /**
     * Тест на добавление опыта и увеличение уровня
     *
     * @throws Exception
     */
    public function testLevelAddExpSuccess(): void
    {
        $level = new Level(
            $accountId = self::DEMO_CHAT_ADMIN,
            'd47f18ce-8854-4b1c-a0f1-d1a681013281',
            '556d9249-5f5f-47d4-b41c-ca580f6c5e23',
            1,
            0,
            0,
            0
        );

        self::assertEquals(1, $level->getLevel());
        self::assertEquals(0, $level->getExp());
        self::assertEquals(0, $level->getExpAtLevel());
        self::assertEquals(200, $level->getExpToLevel());
        self::assertEquals(0, $level->getStatPoints());
        self::assertEquals(0, $level->getSkillPoints());

        // Вариант с повышением уровня на 1
        $level->addExp(200);

        self::assertEquals(2, $level->getLevel());
        self::assertEquals(200, $level->getExp());
        self::assertEquals(0, $level->getExpAtLevel());
        self::assertEquals(350, $level->getExpToLevel());
        self::assertEquals(1 * LevelInterface::ADD_STAT_POINT, $level->getStatPoints());
        self::assertEquals(1 * LevelInterface::ADD_STAT_POINT, $level->getStatPoints());

        // Но опыта может добавиться столько, что будет получено сразу несколько уровней
        $level->addExp(50000);

        self::assertEquals(15, $level->getLevel());
        self::assertEquals(50200, $level->getExp());
        self::assertEquals(8110, $level->getExpAtLevel());
        self::assertEquals(8390, $level->getExpToLevel());
        self::assertEquals(14 * LevelInterface::ADD_STAT_POINT, $level->getStatPoints());
        self::assertEquals(14 * LevelInterface::ADD_SKILL_POINT, $level->getSkillPoints());

    }

    /**
     * Тест на ситуацию, когда добавляется некорректный опыт
     *
     * @throws Exception
     */
    public function testLevelAddExpInvalidExp(): void
    {
        $level = new Level(
            '3544c1bc-8757-47db-9998-6f14522b5252',
            'be5ab64f-3bcb-4dad-b941-c8c87a48a6ca',
            '556d9249-5f5f-47d4-b41c-ca580f6c5e23',
            1,
            0,
            0,
            0,
        );
        $addExp = 0;

        $this->expectException(AppException::class);
        $this->expectExceptionMessage(LevelException::INVALID_ADD_EXP . ': ' . $addExp);
        $level->addExp($addExp);
    }

    /**
     * Тест на ситуацию, когда добавляется опыта больше чем 100 уровень и 100% опыта сверху
     *
     * Другими словами ситуация, когда уровень должен был бы стать 101, но такой уровень недопустим
     *
     * @throws Exception
     */
    public function testLevelAddOverExp(): void
    {
        $level = new Level(
            self::DEMO_USER,
            'be5ab64f-3bcb-4dad-b941-c8c87a48a6ca',
            '556d9249-5f5f-47d4-b41c-ca580f6c5e23',
            100,
            8859190 + 10000,
            0,
            0,
        );

        // Имеем 100 уровень и 10000 опыта сверху
        self::assertEquals(100, $level->getLevel());
        self::assertEquals(10000, $level->getExpAtLevel());
        self::assertEquals(0, $level->getStatPoints());

        // Добавляем 251300 - 10000 - это ровно столько, сколько нужно для следующего уровня, а учитывая, имеющийся опыт это превышает необходимый
        $level->addExp(251300 - 10000);

        // Проверяем, что уровень остался прежним, а до следующего уровня не хватает 1 опыта (и так будет всегда)
        self::assertEquals(100, $level->getLevel());
        self::assertEquals(251300 - 1, $level->getExpAtLevel());

        // Пробуем еще раз добавить опыт
        $level->addExp(1);

        // Результат не изменился
        self::assertEquals(100, $level->getLevel());
        self::assertEquals(251300 - 1, $level->getExpAtLevel());

        // Проверяем также, что и очков характеристик не добавилось
        self::assertEquals(0, $level->getStatPoints());
    }

    /**
     * @return array
     */
    public function createDataProvider(): array
    {
        return [
            [
                '3d465469-c544-4f99-9d5a-fa57c666e434',
                '68891673-056e-451c-bb7b-14415c2a8a10',
                'a16bdcb8-7c8a-4743-bd99-23883fe77778',
                1,
                0,
                0,
                0,
                200,
                0,
                0,
            ],
            [
                '3d465469-c544-4f99-9d5a-fa57c666e434',
                '68891673-056e-451c-bb7b-14415c2a8a11',
                'a16bdcb8-7c8a-4743-bd99-23883fe77778',
                1,
                50,
                3,
                4,
                200,
                50,
                25,
            ],
            [
                '3d465469-c544-4f99-9d5a-fa57c666e434',
                '68891673-056e-451c-bb7b-14415c2a8a12',
                'a16bdcb8-7c8a-4743-bd99-23883fe77778',
                20,
                95790,
                0,
                0,
                14000,
                1000,
                7,
            ],
        ];
    }
}
