<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Character\Collection;

use App\Domain\Account\Character\Collection\CharacterCollectionRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CharacterCollectionRepositoryTest extends AbstractTest
{
    /**
     * @throws AppException
     */
    public function testCharacterCollectionRepositoryGetSuccess(): void
    {
        $mainCharacterId = '2e437627-7b06-456a-b0c6-e70150492901';
        $characters = $this->getRepository()->get($mainCharacterId);

        self::assertCount(1, $characters);

        foreach ($characters as $character) {
            self::assertEquals('277bbc70-cb4a-49a9-8de2-3fd5c1308c01', $character->getId());
            self::assertEquals('/img/avatars/it/analyst/male/01.jpg', $character->getAvatar());
            self::assertEquals('Default', $character->getProfession());
            self::assertEquals('Analyst', $character->getGenesis());
            self::assertEquals(1, $character->getLevel());
        }
    }

    /**
     * @return CharacterCollectionRepository
     * @throws AppException
     */
    private function getRepository(): CharacterCollectionRepository
    {
        return new CharacterCollectionRepository(self::getContainer());
    }
}
