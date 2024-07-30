<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Collection;

use Countable;
use Iterator;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\CollectionTrait;

class CharacterCollection implements Iterator, Countable
{
    use CollectionTrait;

    /**
     * @var CharacterListInterface[]
     */
    private array $elements = [];

    /**
     * @param CharacterListInterface $account
     * @throws AppException
     */
    public function add(CharacterListInterface $account): void
    {
        if (array_key_exists($account->getId(), $this->elements)) {
            throw new AppException(CharacterListException::ALREADY_EXIST);
        }

        $this->elements[$account->getId()] = $account;
    }

    public function current(): CharacterListInterface
    {
        return current($this->elements);
    }
}
