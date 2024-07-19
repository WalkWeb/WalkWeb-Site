<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Domain\Account\Status\AccountStatusInterface;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountBannedPageHandler extends AbstractHandler
{
    /**
     * Print user banned page
     *
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        if ($loginResponse = $this->checkAuth($request)) {
            return $loginResponse;
        }

        if ($this->getUser()->getStatus()->getId() !== AccountStatusInterface::BLOCKED) {
            return $this->redirect('/');
        }

        return $this->render('account/banned');
    }
}
