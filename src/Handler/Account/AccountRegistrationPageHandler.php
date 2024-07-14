<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Domain\Account\AccountException;
use App\Domain\Account\AccountInterface;
use WalkWeb\NW\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountRegistrationPageHandler extends AbstractHandler
{
    /**
     * Print registration page
     *
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        if ($this->container->exist('user')) {
            return $this->redirect('/');
        }

        $ref = $request->ref;

        if (mb_strlen($ref) > AccountInterface::REF_MAX_LENGTH) {
            return $this->render('account/registration', [
                'csrfToken' => $this->container->getCsrf()->getCsrfToken(),
                'error'     => AccountException::INVALID_REF_LENGTH . AccountInterface::REF_MIN_LENGTH . '-' . AccountInterface::REF_MAX_LENGTH,
                'ref'       => $ref,
            ]);
        }

        return $this->render('account/registration', [
            'csrfToken' => $this->container->getCsrf()->getCsrfToken(),
            'ref'       => $ref,
        ]);
    }
}
