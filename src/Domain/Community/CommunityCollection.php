<?php

declare(strict_types=1);

namespace App\Domain\Community;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\CollectionTrait;
use Countable;
use Iterator;

class CommunityCollection implements Iterator, Countable
{
    use CollectionTrait;

    /**
     * @var CommunityInterface[]
     */
    private array $elements = [];

    /**
     * @param CommunityInterface $community
     * @throws AppException
     */
    public function add(CommunityInterface $community): void
    {
        if (array_key_exists($community->getId(), $this->elements)) {
            throw new AppException(CommunityException::ALREADY_EXIST);
        }

        $this->elements[$community->getId()] = $community;
    }

    public function current(): CommunityInterface
    {
        return current($this->elements);
    }
}
