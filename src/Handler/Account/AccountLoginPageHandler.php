<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Handler\AbstractHandler;
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
        $redirectUrl = $this->getRedirectUrl($request);

        if ($this->container->exist('user')) {
            return $this->render('account/login', [
                'error'       => self::ALREADY_AUTH,
                'csrfToken'   => $this->container->getCsrf()->getCsrfToken(),
                'redirectUrl' => $redirectUrl,
            ])->withHeader(self::REDIRECT_HEADER, $redirectUrl);
        }

        return $this->render('account/login', [
            'csrfToken'   => $this->container->getCsrf()->getCsrfToken(),
            'redirectUrl' => $redirectUrl,
        ])->withHeader(self::REDIRECT_HEADER, $redirectUrl);
    }
}
