<?php

declare(strict_types=1);

namespace App\Handler;

use App\Domain\Account\Notice\Action\SendNoticeAction;
use App\Domain\Account\Notice\Action\SendNoticeActionInterface;
use App\Domain\Account\Notice\NoticeRepository;
use App\Domain\Auth\AuthInterface;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

abstract class AbstractHandler extends \WalkWeb\NW\AbstractHandler
{
    public const MISS_USER    = 'Miss user';
    public const INVALID_USER = 'Invalid user';

    private ?SendNoticeActionInterface $sendNoticeAction = null;

    /**
     * @return AuthInterface
     * @throws AppException
     */
    protected function getUser(): AuthInterface
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

    /**
     * @return SendNoticeActionInterface
     */
    protected function getSendNoticeAction(): SendNoticeActionInterface
    {
        if ($this->sendNoticeAction === null) {
            $this->sendNoticeAction = new SendNoticeAction(new NoticeRepository($this->container));
        }

        return $this->sendNoticeAction;
    }

    /**
     * @param Request $request
     * @return Response|null
     * @throws AppException
     */
    protected function checkAuth(Request $request): ?Response
    {
        if (!$this->container->exist('user')) {
            return $this
                ->render('account/login', [
                    'csrfToken'   => $this->container->getCsrf()->getCsrfToken(),
                    'redirectUrl' => $request->getUri(),
                ]);
        }

        return null;
    }
}
