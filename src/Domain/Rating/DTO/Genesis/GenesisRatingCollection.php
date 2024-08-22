<?php

declare(strict_types=1);

namespace App\Domain\Rating\DTO\Genesis;

use Countable;
use Iterator;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\CollectionTrait;

class GenesisRatingCollection implements Iterator, Countable
{
    use CollectionTrait;

    /**
     * @var GenesisRatingInterface[]
     */
    private array $elements = [];

    private int $total = 0;

    /**
     * @param GenesisRatingInterface $notice
     * @throws AppException
     */
    public function add(GenesisRatingInterface $notice): void
    {
        if (array_key_exists($notice->getId(), $this->elements)) {
            throw new AppException(GenesisRatingException::ALREADY_EXIST);
        }

        $this->elements[$notice->getId()] = $notice;
    }

    public function current(): GenesisRatingInterface
    {
        return current($this->elements);
    }
}
