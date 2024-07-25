<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Character;

use App\Domain\Account\AccountException;
use App\Domain\Account\Character\CharacterRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CharacterRepositoryTest extends AbstractTest
{
    /**
     * @throws AppException
     * @throws AccountException
     */
    public function testCharacterRepositoryGetSuccess(): void
    {
        $id = '277bbc70-cb4a-49a9-8de2-3fd5c1308c01';

        $character = $this->getRepository()->get($id);

        self::assertEquals($id, $character->getId());
        self::assertEquals('2e437627-7b06-456a-b0c6-e70150492901', $character->getMainCharacterId());
        self::assertEquals(1, $character->getSeason()->getId());
        self::assertEquals(1, $character->getGenesis()->getId());
        self::assertEquals(1, $character->getProfession()->getId());
        self::assertEquals('/img/avatars/it/analyst/male/01.jpg', $character->getAvatar());
        self::assertEquals(1, $character->getFloor()->getId());
        self::assertEquals(1, $character->getLevel()->getLevel());
        self::assertEquals(0, $character->getLevel()->getExp());
        self::assertEquals(0, $character->getLevel()->getStatPoints());
        self::assertEquals(0, $character->getLevel()->getSkillPoints());
    }

    /**
     * @throws AccountException
     * @throws AppException
     */
    public function testCharacterRepositoryGetNotFound(): void
    {
        self::assertNull($this->getRepository()->get('916714af-9283-4364-9958-9728299e6f58'));
    }

    /**
     * @return CharacterRepository
     * @throws AppException
     */
    private function getRepository(): CharacterRepository
    {
        return new CharacterRepository(self::getContainer());
    }
}
