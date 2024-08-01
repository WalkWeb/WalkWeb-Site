<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class VerifiedEmailPageHandler extends AbstractHandler
{
    /**
     * Отображает страницу с информацией о необходимости подтвердить email
     *
     * Если страницу пытается открыть неавторизованный пользователь - ошибка "вы не авторизованны"
     *
     * Если пользователь уже имеет подтвержденный email - сообщение о том, что все ок, и больше ничего делать не нужно
     *
     * Если пользователь зарегистрирован, но не подтвердил почту - сообщение о необходимости подтверждения
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

        if ($this->container->getUser()->isEmailVerified()) {
            $this->title = 'Email успешно подтвержден';
            $message = 'Вы успешно подтвердили email, ваш аккаунт активирован и все возможности доступны.<br /><br />
                        <a href="/">Перейти на главную</a>';

            return $this->render('account/email_verified', ['message' => $message]);
        }

        $this->title = 'Подтвердите ваш email';
        $message = 'Вам необходимо подтвердить email чтобы активировать аккаунт. Инструкция по активации 
                    отправлена на email указанный при регистрации.<br /><br />
                    Если вам не пришло письмо – свяжитесь с нашей службой поддержки.';

        return $this->render('account/email_verified', ['message' => $message]);
    }
}
