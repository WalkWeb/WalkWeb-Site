<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Domain\Account\AccountException;
use App\Domain\Account\AccountInterface;
use App\Domain\Account\AccountRepository;
use App\Domain\Account\DTO\LoginRequestFactory;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountLoginHandler extends AbstractHandler
{
    private AccountRepository $accountRepository;

    public function __construct(Container $container, ?AccountRepository $accountRepository = null)
    {
        parent::__construct($container);
        $this->accountRepository = $accountRepository ?? new AccountRepository($this->container);
    }

    /**
     * Login user
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

        try {
            $csrfToken = $request->csrf;
            if (!$this->container->getCsrf()->checkCsrfToken($csrfToken ?? '')) {
                throw new AppException('Invalid csrf-token');
            }

            $loginRequest = LoginRequestFactory::create($request->getBody());

            if ($token = $this->accountRepository->auth($loginRequest, KEY)) {
                $this->container->getCookies()->set(AccountInterface::AUTH_TOKEN, $token);
                return $this->redirect($loginRequest->getRedirectUrl());
            }

            return $this->render('account/login', [
                'error'     => AccountException::INVALID_LOGIN_OR_PASSWORD,
                'csrfToken' => $this->container->getCsrf()->getCsrfToken(),
            ]);

        } catch (AppException $e) {
            return $this->render('account/login', [
                'error'     => $e->getMessage(),
                'csrfToken' => $this->container->getCsrf()->getCsrfToken(),
            ]);
        }
    }
}
