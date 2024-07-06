<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\MainCharacter\Level;

use App\Domain\Account\MainCharacter\Level\Level;
use App\Domain\Account\MainCharacter\Level\LevelException;
use App\Domain\Account\MainCharacter\Level\LevelInterface;
use App\Domain\Account\Notice\Action\SendNoticeAction;
use App\Domain\Account\Notice\Action\SendNoticeActionInterface;
use App\Domain\Account\Notice\NoticeRepository;
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
     * @param string $characterId
     * @param int $levelValue
     * @param int $exp
     * @param int $statPoints
     * @param int $expectedExpToLevel
     * @param int $expectedExpAtLevel
     * @param int $expectedExpBarWeight
     * @throws Exception
     */
    public function testLevelCreate(
        string $accountId,
        string $characterId,
        int $levelValue,
        int $exp,
        int $statPoints,
        int $expectedExpToLevel,
        int $expectedExpAtLevel,
        int $expectedExpBarWeight
    ): void
    {
        $level = new Level(
            $accountId,
            $characterId,
            $levelValue,
            $exp,
            $statPoints,
            $this->getSendNoticeAction()
        );

        // Параметры, которые указываются напрямую
        self::assertEquals($accountId, $level->getAccountId());
        self::assertEquals($characterId, $level->getCharacterId());
        self::assertEquals($levelValue, $level->getLevel());
        self::assertEquals($exp, $level->getExp());
        self::assertEquals($statPoints, $level->getStatPoints());

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

        $this->expectException(LevelException::class);
        $this->expectExceptionMessage(LevelException::INVALID_LEVEL . ': ' . $level);
        new Level(
            '3544c1bc-8757-47db-9998-6f14522b5252',
            '556d9249-5f5f-47d4-b41c-ca580f6c5e23',
            $level,
            500,
            10,
            $this->getSendNoticeAction()
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
            $accountId = '1e3a3b27-12da-4c73-a3a7-b83092705bae',
            '556d9249-5f5f-47d4-b41c-ca580f6c5e23',
            1,
            0, 0,
            $this->getSendNoticeAction()
        );

        self::assertEquals(1, $level->getLevel());
        self::assertEquals(0, $level->getExp());
        self::assertEquals(0, $level->getExpAtLevel());
        self::assertEquals(50, $level->getExpToLevel());
        self::assertEquals(0, $level->getStatPoints());

        // Вариант с повышением уровня на 1
        $level->addExp(50);

        self::assertEquals(2, $level->getLevel());
        self::assertEquals(50, $level->getExp());
        self::assertEquals(0, $level->getExpAtLevel());
        self::assertEquals(130, $level->getExpToLevel());
        self::assertEquals(1 * LevelInterface::ADD_STAT_POINT, $level->getStatPoints());

        // Но опыта может добавиться столько, что будет получено сразу несколько уровней
        $level->addExp(5000);

        self::assertEquals(9, $level->getLevel());
        self::assertEquals(5050, $level->getExp());
        self::assertEquals(980, $level->getExpAtLevel());
        self::assertEquals(1350, $level->getExpToLevel());
        self::assertEquals(8 * LevelInterface::ADD_STAT_POINT, $level->getStatPoints());

        // TODO
        // Проверяем наличие уведомлений
//        $reflectionClass = new ReflectionClass($noticeRepository);
//        $reflectionProperty = $reflectionClass->getProperty('notices');
//        $reflectionProperty->setAccessible(true);
//
//        $notices = $reflectionProperty->getValue($noticeRepository);
//
//        self::assertCount(8, $notices);
//
//        /** @var NoticeInterface $notice */
//        foreach ($notices as $notice) {
//            self::assertEquals($accountId, $notice->getAccountId());
//            self::assertEquals(LevelInterface::NEW_LEVEL_MESSAGE, $notice->getMessage());
//            self::assertEquals(NoticeInterface::TYPE_INFO, $notice->getType());
//        }
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
            '556d9249-5f5f-47d4-b41c-ca580f6c5e23',
            1,
            0,
            0,
            $this->getSendNoticeAction()
        );
        $addExp = 0;

        $this->expectException(LevelException::class);
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
            '1e3a3b27-12da-4c73-a3a7-b83092705bae',
            '556d9249-5f5f-47d4-b41c-ca580f6c5e23',
            100,
            2396700 + 10000,
            0,
            $this->getSendNoticeAction()
        );

        // Имеем 100 уровень и 10000 опыта сверху
        self::assertEquals(100, $level->getLevel());
        self::assertEquals(10000, $level->getExpAtLevel());
        self::assertEquals(0, $level->getStatPoints());

        // Добавляем 63100 - это ровно столько, сколько нужно для следующего уровня, а учитывая, имеющийся опыт это превышает необходимый
        $level->addExp(63100);

        // Проверяем, что уровень остался прежним, а до следующего уровня не хватает 1 опыта (и так будет всегда)
        self::assertEquals(100, $level->getLevel());
        self::assertEquals(63100 - 1, $level->getExpAtLevel());

        // Пробуем еще раз добавить опыт
        $level->addExp(1);

        // Результат не изменился
        self::assertEquals(100, $level->getLevel());
        self::assertEquals(63100 - 1, $level->getExpAtLevel());

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
                'a16bdcb8-7c8a-4743-bd99-23883fe77778',
                1,
                0,
                0,
                50,
                0,
                0,
            ],
            [
                '3d465469-c544-4f99-9d5a-fa57c666e434',
                'a16bdcb8-7c8a-4743-bd99-23883fe77778',
                1,
                25,
                3,
                50,
                25,
                50,
            ],
            [
                '3d465469-c544-4f99-9d5a-fa57c666e434',
                'a16bdcb8-7c8a-4743-bd99-23883fe77778',
                45,
                296400,
                0,
                17600,
                200,
                1,
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
