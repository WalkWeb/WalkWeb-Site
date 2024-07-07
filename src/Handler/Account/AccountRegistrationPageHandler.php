<?php

declare(strict_types=1);

namespace App\Handler\Account;

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
        // TODO Проверка на то, что пользователь уже авторизован

        return $this->render('account/registration', [
            'csrfToken' => $this->container->getCsrf()->getCsrfToken(),
            'ref'       => $request->ref,
        ]);
    }
}
