<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Domain\Account\AccountInterface;
use App\Domain\Account\Notice\Action\SendNoticeAction;
use App\Domain\Account\Notice\NoticeRepository;
use App\Domain\Auth\AuthRepository;
use WalkWeb\NW\AbstractMiddleware;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AuthMiddleware extends AbstractMiddleware
{
    /**
     * @param Request $request
     * @param callable $handler
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request, callable $handler): Response
    {
        if ($authToken = $this->container->getCookies()->get(AccountInterface::AUTH_TOKEN)) {
            $repository = new AuthRepository($this->container);
            if ($user = $repository->get($authToken, new SendNoticeAction(new NoticeRepository($this->container)))) {
                // TODO
                //$this->container->setTemplate($user->getTemplate());
                $this->container->set('user', $user);
            } else {
                $this->container->getCookies()->delete(AccountInterface::AUTH_TOKEN);
            }
        }

        // TODO Check ban user

        // TODO Check no end register user

        return $handler($request);
    }
}
