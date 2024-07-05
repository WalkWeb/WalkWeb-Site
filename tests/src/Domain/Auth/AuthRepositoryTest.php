<?php

declare(strict_types=1);

namespace Test\src\Domain\Auth;

use App\Domain\Account\AccountException;
use App\Domain\Account\Notice\NoticeCollection;
use App\Domain\Auth\AuthRepository;
use Exception;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class AuthRepositoryTest extends AbstractTest
{
    /**
     * Test on success get Auth
     *
     * @throws Exception
     */
    public function testAuthRepositoryGetSuccess(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';

        $auth = $this->getRepository()->get($authToken);

        self::assertEquals('1e3a3b27-12da-4c73-a3a7-b83092705bae', $auth->getId());
        self::assertEquals('DemoUser', $auth->getName());
        self::assertEquals(10, $auth->getGroup()->getId());
        self::assertEquals(1, $auth->getStatus()->getId());
        self::assertTrue($auth->isCanLike());

        self::assertEquals('2dad01e1-af9d-479d-9f48-92823f585827', $auth->getEnergy()->getId());
        self::assertEquals(150, $auth->getEnergy()->getEnergy());
        self::assertEquals(150, $auth->getEnergy()->getMaxEnergy());
        self::assertEquals(1583780978.0000, $auth->getEnergy()->getUpdatedAt());
        self::assertEquals(0, $auth->getEnergy()->getResidue());

        // TODO Mock
        self::assertEquals('', $auth->getAvatar());
        self::assertEquals(1, $auth->getLevel());
        self::assertEquals(0, $auth->getStatPoints());
        self::assertEquals(new NoticeCollection(), $auth->getNotices());
    }

    /**
     * Test on fail get Auth at unknown auth token
     *
     * @throws Exception
     */
    public function testAuthRepositoryGetNotFound(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage(AccountException::NOT_FOUND);
        $this->getRepository()->get('unknown_token');
    }

    /**
     * @return AuthRepository
     * @throws AppException
     */
    private function getRepository(): AuthRepository
    {
        return new AuthRepository(self::getContainer());
    }
}
