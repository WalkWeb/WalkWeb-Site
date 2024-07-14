<?php

declare(strict_types=1);

namespace App\Handler\Account;

use WalkWeb\NW\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountLoginPageHandler extends AbstractHandler
{
    public const ALREADY_AUTH = 'Вы уже авторизованы';

    /**
     * Print login page
     *
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        if ($this->container->exist('user')) {
            return $this->render('account/login', [
                'error'     => self::ALREADY_AUTH,
                'csrfToken' => $this->container->getCsrf()->getCsrfToken(),
            ]);
        }

        return $this->render('account/login', [
            'csrfToken' => $this->container->getCsrf()->getCsrfToken(),
        ]);
    }
}
