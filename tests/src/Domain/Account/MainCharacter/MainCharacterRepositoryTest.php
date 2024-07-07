<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\MainCharacter;

use App\Domain\Account\MainCharacter\MainCharacterException;
use App\Domain\Account\MainCharacter\MainCharacterRepository;
use Exception;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class MainCharacterRepositoryTest extends AbstractTest
{
    /**
     * Test on success get MainCharacter from array data
     *
     * @dataProvider successDataProvider
     * @param string $id
     * @param array $data
     * @throws AppException
     */
    public function testMainCharacterRepositoryGetSuccess(string $id, array $data): void
    {
        $mainCharacter = $this->getRepository()->get($id, $this->getSendNoticeAction());

        self::assertEquals($id, $mainCharacter->getId());
        self::assertEquals($data['account_id'], $mainCharacter->getAccountId());
        self::assertEquals($data['era_id'], $mainCharacter->getEra()->getId());
        self::assertEquals($data['level'], $mainCharacter->getLevel()->getLevel());
        self::assertEquals($data['exp'], $mainCharacter->getLevel()->getExp());
        self::assertEquals($data['energy_bonus'], $mainCharacter->getEnergyBonus());
        self::assertEquals($data['upload_bonus'], $mainCharacter->getUploadBonus());
        self::assertEquals($data['stats_point'], $mainCharacter->getLevel()->getStatPoints());
    }

    /**
     * Test on failure get MainCharacter when an unknown id
     *
     * @throws AppException
     */
    public function testMainCharacterRepositoryGetNotFound(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage(MainCharacterException::NOT_FOUND);
        $this->getRepository()->get('d031d68e-a97f-40fc-9b82-68fc2a440783', $this->getSendNoticeAction());
    }

    /**
     * Test on success update MainCharacter
     *
     * @throws AppException
     * @throws Exception
     */
    public function testMainCharacterRepositoryUpdateSuccess(): void
    {
        $mainCharacter = $this->getRepository()->get('2e437627-7b06-456a-b0c6-e70150492901', $this->getSendNoticeAction());

        self::assertEquals(1, $mainCharacter->getLevel()->getLevel());
        self::assertEquals(0, $mainCharacter->getLevel()->getExp());
        self::assertEquals(0, $mainCharacter->getLevel()->getStatPoints());

        $mainCharacter->getLevel()->addExp(500);

        $this->getRepository()->update($mainCharacter);

        $mainCharacter = $this->getRepository()->get('2e437627-7b06-456a-b0c6-e70150492901', $this->getSendNoticeAction());

        self::assertEquals(4, $mainCharacter->getLevel()->getLevel());
        self::assertEquals(500, $mainCharacter->getLevel()->getExp());
        self::assertEquals(15, $mainCharacter->getLevel()->getStatPoints());
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                '2e437627-7b06-456a-b0c6-e70150492901',
                [
                    'account_id'   => self::DEMO_USER,
                    'era_id'       => 1,
                    'level'        => 1,
                    'exp'          => 0,
                    'energy_bonus' => 0,
                    'upload_bonus' => 0,
                    'stats_point'  => 0,
                ],
            ],
            [
                '2e437627-7b06-456a-b0c6-e70150492902',
                [
                    'account_id'   => self::BLOCKED_USER,
                    'era_id'       => 1,
                    'level'        => 2,
                    'exp'          => 54,
                    'energy_bonus' => 0,
                    'upload_bonus' => 0,
                    'stats_point'  => 5,
                ],
            ],
            [
                '2e437627-7b06-456a-b0c6-e70150492903',
                [
                    'account_id'   => self::NO_END_REG_USER,
                    'era_id'       => 1,
                    'level'        => 3,
                    'exp'          => 150,
                    'energy_bonus' => 0,
                    'upload_bonus' => 0,
                    'stats_point'  => 10,
                ],
            ],
        ];
    }

    /**
     * @return MainCharacterRepository
     * @throws AppException
     */
    private function getRepository(): MainCharacterRepository
    {
        return new MainCharacterRepository(self::getContainer());
    }
}
