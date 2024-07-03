<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Domain\Account\AccountFactory;
use App\Domain\Account\AccountRepository;
use WalkWeb\NW\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountRegistrationHandler extends AbstractHandler
{
    /**
     * Создание нового пользователя на основе данных из формы регистрации
     *
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        try {
            $csrfToken = $request->csrf;
            if (!$this->container->getCsrf()->checkCsrfToken($csrfToken ?? '')) {
                throw new AppException('Invalid csrf-token');
            }

            $body = $request->getBody();

            $body['template'] = TEMPLATE_DEFAULT;
            $body['ip'] = $this->getIp($request);
            $body['floor_id'] = (int)$body['floor_id'];

            // TODO Add
            $body['ref'] = '';
            $body['user_agent'] = '';

            $account = AccountFactory::createNew($body, KEY);

            $repository = new AccountRepository($this->container);
            $repository->add($account);

            return $this->render('account/registration_complete');

        } catch (AppException $e) {
            return $this->render('account/registration', ['error' => $e->getMessage()]);
        }
    }

    private function getIp(Request $request): string
    {
        if (array_key_exists('HTTP_CLIENT_IP', $request->getServer())) {
            return (string)$request->getServer()['HTTP_CLIENT_IP'];
        }

        if (array_key_exists('HTTP_X_FORWARDED_FOR', $request->getServer())) {
            return (string)$request->getServer()['HTTP_X_FORWARDED_FOR'];
        }

        if (array_key_exists('REMOTE_ADDR', $request->getServer())) {
            return (string)$request->getServer()['REMOTE_ADDR'];
        }

        return 'undefined';
    }
}
