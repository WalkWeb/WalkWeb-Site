<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Domain\Auth\AuthRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountCheckMailHandler extends AbstractHandler
{
    /**
     * Обрабатывает ссылку о подтверждении email из почты
     *
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        if (!$this->container->exist('user')) {
            $this->title = 'Необходима авторизация';
            $message = '<p>Необходима авторизация</p>
                        <p><a href="/login">Перейти на страницу авторизации</a></p>';

            return $this->render('account/email_verified', ['message' => $message]);
        }

        $user = $this->getUser();

        if ($user->isEmailVerified()) {
            return $this->redirect('/verified/email');
        }

        $token = $request->token;

        if ($user->getVerifiedToken() !== $token) {
            $this->title = 'Ошибка подтверждения email';
            $message = '<p>Указан некорректный токен подтверждения email.</p>';
            return $this->render('account/email_verified', ['message' => $message]);
        }

        $user->emailVerified();
        $repository = new AuthRepository($this->container);
        $repository->saveVerified($user);

        return $this->redirect('/verified/email');
    }
}
