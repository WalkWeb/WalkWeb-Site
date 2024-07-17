<?php

declare(strict_types=1);

namespace App\Handler;

use App\Domain\Auth\AuthInterface;
use WalkWeb\NW\AppException;

abstract class AbstractHandler extends \WalkWeb\NW\AbstractHandler
{
    public const MISS_USER    = 'Miss user';
    public const INVALID_USER = 'Invalid user';

    /**
     * @return AuthInterface
     * @throws AppException
     */
    public function getUser(): AuthInterface
    {
        if (!$this->container->exist('user')) {
            throw new AppException(self::MISS_USER);
        }

        $user = $this->container->getUser();

        if (!($user instanceof AuthInterface)) {
            throw new AppException(self::INVALID_USER);
        }

        return $user;
    }

    // TODO getSendNoticeAction()
}
