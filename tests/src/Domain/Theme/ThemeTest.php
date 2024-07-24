<?php

declare(strict_types=1);

namespace Test\src\Domain\Theme;

use App\Domain\Account\AccountException;
use App\Domain\Theme\Theme;
use App\Domain\Theme\ThemeInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class ThemeTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param int $id
     * @param string $expectedName
     * @throws AppException
     */
    public function testThemeCreateSuccess(int $id, string $expectedName): void
    {
        $theme = new Theme($id);

        $this->assertEquals($id, $theme->getId());
        $this->assertEquals($expectedName, $theme->getName());
    }

    /**
     * @dataProvider failDataProvider
     * @param int $id
     * @param string $error
     * @throws AppException
     */
    public function testThemeCreateFail(int $id, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        new Theme($id);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                ThemeInterface::THEME_IT,
                'it',
            ],
            [
                ThemeInterface::THEME_GAME,
                'game',
            ],
            [
                ThemeInterface::THEME_VIDEO,
                'video',
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
                11,
                AccountException::UNKNOWN_THEME_ID . ': 11',
            ],
        ];
    }
}
