<?php

declare(strict_types=1);

namespace App\Domain\Account;

use App\Domain\Account\DTO\LoginRequest;
use App\Domain\Account\Energy\EnergyFactory;
use App\Domain\Account\Energy\EnergyInterface;
use App\Domain\Account\Energy\EnergyRepository;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Response;

class AccountRepository
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $name
     * @return AccountInterface
     * @throws AccountException
     * @throws AppException
     */
    public function get(string $name): AccountInterface
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `accounts` WHERE `name` = ?',
            [['type' => 's', 'value' => $name]],
            true
        );

        if (!$data) {
            throw new AppException(AccountException::NOT_FOUND, Response::NOT_FOUND);
        }

        return AccountFactory::createFromDB($data);
    }

    /**
     * @param AccountInterface $account
     * @throws AppException
     */
    public function add(AccountInterface $account): void
    {
        if ($this->existAccountByLogin($account->getLogin())) {
            throw new AppException(AccountException::LOGIN_ALREADY_EXIST);
        }

        if ($this->existAccountByName($account->getName())) {
            throw new AppException(AccountException::NAME_ALREADY_EXIST);
        }

        if ($this->existAccountByEmail($account->getEmail())) {
            throw new AppException(AccountException::EMAIL_ALREADY_EXIST);
        }

        $this->container->getConnectionPool()->getConnection()->query(
            'INSERT INTO `accounts` 
                (
                 `id`, `login`, `name`, `password`, `email`, `email_verified`, `reg_complete`, `auth_token`, 
                 `verified_token`, `template`, `ip`, `ref`, `floor_id`, `status_id`, `group_id`, `energy_id`
                ) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                ['type' => 's', 'value' => $account->getId()],
                ['type' => 's', 'value' => $account->getLogin()],
                ['type' => 's', 'value' => $account->getName()],
                ['type' => 's', 'value' => $account->getPassword()],
                ['type' => 's', 'value' => $account->getEmail()],
                ['type' => 'i', 'value' => (int)$account->isEmailVerified()],
                ['type' => 'i', 'value' => (int)$account->isRegComplete()],
                ['type' => 's', 'value' => $account->getAuthToken()],
                ['type' => 's', 'value' => $account->getVerifiedToken()],
                ['type' => 's', 'value' => $account->getTemplate()],
                ['type' => 's', 'value' => $account->getIp()],
                ['type' => 's', 'value' => $account->getRef()],
                ['type' => 'i', 'value' => $account->getFloor()->getId()],
                ['type' => 'i', 'value' => $account->getStatus()->getId()],
                ['type' => 'i', 'value' => $account->getGroup()->getId()],
                ['type' => 's', 'value' => $this->createEnergy()->getId()],
            ]
        );
    }

    /**
     * Проверяет, существует ли пользователь с указанным логином и паролем, и если есть - возвращает его авторизационный
     * токен
     *
     * @param LoginRequest $request
     * @param string $hashKey
     * @return string|null
     * @throws AppException
     */
    public function auth(LoginRequest $request, string $hashKey): ?string
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT `auth_token`, `password` FROM `accounts` WHERE `login` = ?',
            [
                ['type' => 's', 'value' => $request->getLogin()],
            ],
            true
        );

        if (
            $data &&
            array_key_exists('password', $data) &&
            array_key_exists('auth_token', $data) &&
            password_verify($request->getPassword() . $hashKey, $data['password'])
        ) {
            return $data['auth_token'];
        }

        return null;
    }

    /**
     * @param string $login
     * @return bool
     * @throws AppException
     */
    private function existAccountByLogin(string $login): bool
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `accounts` WHERE `login` = ?',
            [['type' => 's', 'value' => $login]],
        );

        return count($data) > 0;
    }

    /**
     * @param string $name
     * @return bool
     * @throws AppException
     */
    private function existAccountByName(string $name): bool
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `accounts` WHERE `name` = ?',
            [['type' => 's', 'value' => $name]],
        );

        return count($data) > 0;
    }

    /**
     * @param string $email
     * @return bool
     * @throws AppException
     */
    private function existAccountByEmail(string $email): bool
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `accounts` WHERE `email` = ?',
            [['type' => 's', 'value' => $email]],
        );

        return count($data) > 0;
    }

    /**
     * @return EnergyInterface
     * @throws AppException
     */
    private function createEnergy(): EnergyInterface
    {
        $energy = EnergyFactory::createNew();
        $repository = new EnergyRepository($this->container);
        $repository->add($energy);
        return $energy;
    }
}
