<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Domain\Account\AccountException;
use App\Domain\Account\AccountInterface;
use App\Domain\Account\AccountRepository;
use App\Domain\Account\DTO\LoginRequestFactory;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountLoginHandler extends AbstractHandler
{
    /**
     * Авторизует пользователя
     *
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        // TODO Проверка на уже существующую авторизацию

        try {
            $csrfToken = $request->csrf;
            if (!$this->container->getCsrf()->checkCsrfToken($csrfToken ?? '')) {
                throw new AppException('Invalid csrf-token');
            }

            $loginRequest = LoginRequestFactory::create($request->getBody());
            $repository = new AccountRepository($this->container);
            if ($token = $repository->auth($loginRequest, KEY)) {
                $this->container->getCookies()->set(AccountInterface::AUTH_TOKEN, $token);
                return $this->redirect('/');
            }

            return $this->render('account/login', ['error' => AccountException::INVALID_LOGIN_OR_PASSWORD]);

        } catch (AppException $e) {
            return $this->render('account/login', ['error' => $e->getMessage()]);
        }
    }
}
