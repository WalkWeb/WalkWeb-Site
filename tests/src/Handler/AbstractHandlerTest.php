<?php

declare(strict_types=1);

namespace Test\src\Handler;

use App\Handler\AbstractHandler;
use stdClass;
use Test\AbstractTest;
use Test\Mock\TestHandler;
use WalkWeb\NW\AppException;

class AbstractHandlerTest extends AbstractTest
{
    /**
     * @throws AppException
     */
    public function testAbstractHandlerMissUser(): void
    {
        $handler = new TestHandler(self::createContainer());

        $this->expectException(AppException::class);
        $this->expectExceptionMessage(AbstractHandler::MISS_USER);
        $handler->getUser();
    }

    /**
     * @throws AppException
     */
    public function testAbstractHandlerInvalidUser(): void
    {
        $container = self::createContainer();
        $container->set('user', new stdClass());

        $handler = new TestHandler($container);

        $this->expectException(AppException::class);
        $this->expectExceptionMessage(AbstractHandler::INVALID_USER);
        $handler->getUser();
    }
}
