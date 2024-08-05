<?php

declare(strict_types=1);

namespace App\Domain\Post\Tag;

use App\Domain\Pieces\Interfaces\ArrayableInterface;
use Countable;
use Iterator;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\CollectionTrait;

class TagCollection implements Iterator, Countable, ArrayableInterface
{
    use CollectionTrait;

    /**
     * @var TagInterface[]
     */
    private array $elements = [];

    /**
     * @param TagInterface $tag
     * @throws AppException
     */
    public function add(TagInterface $tag): void
    {
        if (array_key_exists($tag->getId(), $this->elements)) {
            throw new AppException(TagException::ALREADY_EXIST);
        }

        $this->elements[$tag->getId()] = $tag;
    }

    /**
     * @return TagInterface
     */
    public function current(): TagInterface
    {
        return current($this->elements);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = [];

        foreach ($this as $tag) {
            $array[] = $tag->toArray();
        }

        return $array;
    }
}
