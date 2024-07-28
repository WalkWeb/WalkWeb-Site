<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Character\Avatar;

use App\Domain\Account\Character\Avatar\AvatarRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class AvatarRepositoryTest extends AbstractTest
{
    /**
     * @dataProvider getSuccessDataProvider
     * @param int $id
     * @param int $genesisId
     * @param int $floorId
     * @param string $originUrl
     * @param string $smallUrl
     * @throws AppException
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
     * @throws AppException
     */
    public function testAvatarRepositoryGetNotFound(): void
    {
        self::assertNull($this->getRepository()->get(1213));
    }

    /**
     * @dataProvider getForRegisterSuccessDataProvider
     * @param int $id
     * @param int $genesisId
     * @param int $floorId
     * @param string $originUrl
     * @param string $smallUrl
     * @throws AppException
     */
    public function testAvatarRepositoryGetForRegisterSuccess(
        int $id,
        int $genesisId,
        int $floorId,
        string $originUrl,
        string $smallUrl
    ): void
    {
        $avatar = $this->getRepository()->getForRegister($id, $genesisId, $floorId);

        self::assertEquals($id, $avatar->getId());
        self::assertEquals($genesisId, $avatar->getGenesis()->getId());
        self::assertEquals($floorId, $avatar->getFloor()->getId());
        self::assertEquals($originUrl, $avatar->getOriginUrl());
        self::assertEquals($smallUrl, $avatar->getSmallUrl());
    }

    /**
     * @throws AppException
     */
    public function testAvatarRepositoryGetForRegisterNotFound(): void
    {
        self::assertNull($this->getRepository()->getForRegister(43, 1, 1));
    }

    /**
     * @return array
     */
    public function getSuccessDataProvider(): array
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
     * @return array
     */
    public function getForRegisterSuccessDataProvider(): array
    {
        return [
            [
                31,
                3,
                2,
                '/img/avatars/it/devops/female/01.jpg',
                '/img/avatars/it/devops/female/01s.jpg',
            ],
            [
                13,
                2,
                1,
                '/img/avatars/it/designer/male/01.jpg',
                '/img/avatars/it/designer/male/01s.jpg',
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
