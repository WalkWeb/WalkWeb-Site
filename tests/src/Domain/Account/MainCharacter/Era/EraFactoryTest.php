<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\MainCharacter\Era;

use App\Domain\Account\MainCharacter\Era\EraException;
use App\Domain\Account\MainCharacter\Era\EraFactory;
use Test\AbstractTest;

class EraFactoryTest extends AbstractTest
{
    /**
     * Тест на создание эпохи через фабрику
     *
     * @dataProvider createDataProvider
     * @param int $id
     * @param string $expectedName
     * @param bool $expectedActive
     * @throws EraException
     */
    public function testEraFactoryCreateSuccess(int $id, string $expectedName, bool $expectedActive): void
    {
        $era = EraFactory::create($id);

        self::assertEquals($id, $era->getId());
        self::assertEquals($expectedName, $era->getName());
        self::assertEquals($expectedActive, $era->isActual());
    }

    /**
     * Тест на ситуацию, когда передан неизвестный id эпохи
     *
     * @throws EraException
     */
    public function testEraFactoryCreateUnknownId(): void
    {
        $id = 99;
        $this->expectException(EraException::class);
        $this->expectExceptionMessage(EraException::UNKNOWN_ERA . ": $id");
        EraFactory::create($id);
    }

    /**
     * @return array
     */
    public function createDataProvider(): array
    {
        return [
            [
                1,
                'Alpha',
                true,
            ],
            [
                2,
                'Beta',
                false,
            ],
        ];
    }
}
