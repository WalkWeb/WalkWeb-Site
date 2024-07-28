<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Character\Profession;

use App\Domain\Account\Character\Profession\ProfessionRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class ProfessionRepositoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param int $id
     * @param int $genesisId
     * @param string $icon
     * @param string $nameMale
     * @param string $nameFemale
     * @throws AppException
     */
    public function testProfessionRepositoryGetSuccess(
        int $id,
        int $genesisId,
        string $icon,
        string $nameMale,
        string $nameFemale
    ): void
    {
        $profession = $this->getRepository()->get($id, $genesisId);

        self::assertEquals($id, $profession->getId());
        self::assertEquals($genesisId, $profession->getGenesis()->getId());
        self::assertEquals($icon, $profession->getIcon());
        self::assertEquals($nameMale, $profession->getNameMale());
        self::assertEquals($nameFemale, $profession->getNameFemale());
    }

    /**
     * @throws AppException
     */
    public function testProfessionRepositoryGetNotFound(): void
    {
        self::assertNull($this->getRepository()->get(123, 1));
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                1,
                1,
                '/img/icon/genesis_default.png',
                'Default',
                'Default',
            ],
            [
                6,
                6,
                '/img/icon/genesis_default.png',
                'Default',
                'Default',
            ],
        ];
    }

    /**
     * @return ProfessionRepository
     * @throws AppException
     */
    private function getRepository(): ProfessionRepository
    {
        return new ProfessionRepository(self::getContainer());
    }
}
