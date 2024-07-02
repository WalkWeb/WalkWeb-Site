<?php

declare(strict_types=1);

namespace App\Domain\Account;

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
        $data =  $this->container->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `accounts` WHERE `name` = ?',
            [['type' => 's', 'value' => $name]],
            true
        );

        if (!$data) {
            throw new AppException(AccountException::NOT_FOUND, Response::NOT_FOUND);
        }

        return AccountFactory::createFromDB($data);
    }
}
