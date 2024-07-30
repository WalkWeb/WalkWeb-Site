<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\MainCharacter;

use App\Domain\Account\AccountFactory;
use App\Domain\Account\AccountRepository;
use App\Domain\Account\DTO\CreateAccountRequest;
use App\Domain\Account\MainCharacter\MainCharacterException;
use App\Domain\Account\MainCharacter\MainCharacterFactory;
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
        $mainCharacterId = '2e437627-7b06-456a-b0c6-e70150492910';

        $mainCharacter = $this->getRepository()->get($mainCharacterId, $this->getSendNoticeAction());

        self::assertEquals(1, $mainCharacter->getLevel()->getLevel());
        self::assertEquals(0, $mainCharacter->getLevel()->getExp());
        self::assertEquals(0, $mainCharacter->getLevel()->getStatPoints());

        $mainCharacter->getLevel()->addExp(500);

        $this->getRepository()->update($mainCharacter);

        $mainCharacter = $this->getRepository()->get($mainCharacterId, $this->getSendNoticeAction());

        self::assertEquals(4, $mainCharacter->getLevel()->getLevel());
        self::assertEquals(500, $mainCharacter->getLevel()->getExp());
        self::assertEquals(15, $mainCharacter->getLevel()->getStatPoints());
    }

    /**
     * Test on add new MainCharacter
     *
     * @dataProvider addDataProvider
     * @param CreateAccountRequest $request
     * @throws AppException
     */
    public function testMainCharacterRepositoryAddSuccess(CreateAccountRequest $request): void
    {
        $container = self::getContainer();
        $sendNotice = $this->getSendNoticeAction();
        $account = AccountFactory::createNew($request, $this->getAvatar(), 'hash_key');
        $accountRepository = new AccountRepository($container);
        $mainCharacterRepository = new MainCharacterRepository($container);

        $accountRepository->add($account);
        $mainCharacter = MainCharacterFactory::createNew($account->getId(), $sendNotice);

        $mainCharacterRepository->add($mainCharacter);
        $entityCharacter = $mainCharacterRepository->get($mainCharacter->getId(), $sendNotice);

        self::assertEquals($mainCharacter->getId(), $entityCharacter->getId());
        self::assertEquals($account->getId(), $entityCharacter->getAccountId());
        self::assertEquals(1, $entityCharacter->getEra()->getId());
        self::assertEquals(1, $entityCharacter->getLevel()->getLevel());
        self::assertEquals(0, $entityCharacter->getLevel()->getExp());
        self::assertEquals(0, $entityCharacter->getEnergyBonus());
        self::assertEquals(0, $entityCharacter->getUploadBonus());
        self::assertEquals(0, $entityCharacter->getLevel()->getStatPoints());
    }

    /**
     * @throws AppException
     */
    public function testMainCharacterRepositoryAddUnknownAccountId(): void
    {
        $mainCharacter = MainCharacterFactory::createNew(
            '51fd0337-fa0b-439a-9033-240d78af57e3',
            $this->getSendNoticeAction()
        );

        $this->expectException(AppException::class);
        $this->expectExceptionMessage(MainCharacterRepository::ADD_SQL);
        $this->getRepository()->add($mainCharacter);
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
     * @return array
     */
    public function addDataProvider(): array
    {
        return [
            [
                new CreateAccountRequest(
                    'User',
                    'mail1@gmail.com',
                    '123456',
                    1,
                    3,
                    3,
                    16,
                    'ref_link1',
                    'undefined',
                    '127.0.0.1',
                ),
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
