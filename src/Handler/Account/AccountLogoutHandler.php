<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Domain\Account\AccountInterface;
use WalkWeb\NW\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountLogoutHandler extends AbstractHandler
{
    /**
     * Logout user (remove authorization token from cookie)
     *
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        $this->container->getCookies()->delete(AccountInterface::AUTH_TOKEN);
        return $this->redirect('/');
    }
}
