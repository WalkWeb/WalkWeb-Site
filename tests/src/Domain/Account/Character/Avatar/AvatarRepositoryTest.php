<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Character\Avatar;

use App\Domain\Account\AccountException;
use App\Domain\Account\Character\Avatar\AvatarRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class AvatarRepositoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param int $id
     * @param int $genesisId
     * @param int $floorId
     * @param string $originUrl
     * @param string $smallUrl
     * @throws AppException
     * @throws AccountException
     */
    public function testAvatarRepositoryGetSuccess(
        int $id,
        int $genesisId,
        int $floorId,
        string $originUrl,
        string $smallUrl
    ): void
    {
        $avatar = $this->getRepository()->get($id);

        self::assertEquals($id, $avatar->getId());
        self::assertEquals($genesisId, $avatar->getGenesis()->getId());
        self::assertEquals($floorId, $avatar->getFloor()->getId());
        self::assertEquals($originUrl, $avatar->getOriginUrl());
        self::assertEquals($smallUrl, $avatar->getSmallUrl());
    }

    /**
     * @throws AccountException
     * @throws AppException
     */
    public function testAvatarRepositoryGetNotFound(): void
    {
        self::assertNull($this->getRepository()->get(1213));
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
                1,
                '/img/avatars/it/analyst/male/01.jpg',
                '/img/avatars/it/analyst/male/01s.jpg',
            ],
            [
                21,
                2,
                2,
                '/img/avatars/it/designer/female/03.jpg',
                '/img/avatars/it/designer/female/03s.jpg',
            ],
        ];
    }

    /**
     * @return AvatarRepository
     * @throws AppException
     */
    private function getRepository(): AvatarRepository
    {
        return new AvatarRepository(self::getContainer());
    }
}
