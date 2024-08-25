<?php

declare(strict_types=1);

namespace Test\src\Domain\Community;

use App\Domain\Community\CommunityRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CommunityRepositoryTest extends AbstractTest
{
    /**
     * @dataProvider getAllDataProvider
     * @param array $expectedNames
     * @throws AppException
     */
    public function testCommunityRepositoryGetAll(array $expectedNames): void
    {
        $communities = $this->getRepository()->getAll();

        self::assertCount(6, $communities);

        $i = 0;
        foreach ($communities as $community) {
            self::assertEquals($expectedNames[$i], $community->getName());
            $i++;
        }
    }

    /**
     * @return array
     */
    public function getAllDataProvider(): array
    {
        return [
            [
                [
                    'Diablo 2: База знаний',
                    'Ведьмак 3: База знаний',
                    'Fallout 4: База знаний',
                    'Скайрим: База знаний',
                    'Path of Exile: База знаний',
                    'Divine Divinity',
                ],
            ],
        ];
    }

    /**
     * @return CommunityRepository
     * @throws AppException
     */
    private function getRepository(): CommunityRepository
    {
        return new CommunityRepository(self::getContainer());
    }
}
