<?php

declare(strict_types=1);

namespace App\Domain\Account\Collection;

use App\Domain\Account\AccountException;
use Countable;
use Iterator;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\CollectionTrait;

class AccountCollection implements Iterator, Countable
{
    use CollectionTrait;

    /**
     * @var AccountListInterface[]
     */
    private array $elements = [];

    /**
     * @param AccountListInterface $account
     * @throws AppException
     */
    public function add(AccountListInterface $account): void
    {
        if (array_key_exists($account->getId(), $this->elements)) {
            throw new AppException(AccountException::ALREADY_EXIST);
        }

        $this->elements[$account->getId()] = $account;
    }

    public function current(): AccountListInterface
    {
        return current($this->elements);
    }
}
