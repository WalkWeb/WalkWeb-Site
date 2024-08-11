<?php

declare(strict_types=1);

namespace App\Domain\Post\Collection;

use App\Domain\Post\PostException;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\CollectionTrait;

use Countable;
use Iterator;

class PostCollection implements Iterator, Countable
{
    use CollectionTrait;

    /**
     * @var PostListInterface[]
     */
    private array $elements = [];

    /**
     * @param PostListInterface $post
     * @throws AppException
     */
    public function add(PostListInterface $post): void
    {
        if (array_key_exists($post->getId(), $this->elements)) {
            throw new AppException(PostException::ALREADY_EXIST);
        }

        $this->elements[$post->getId()] = $post;
    }

    public function current(): PostListInterface
    {
        return current($this->elements);
    }
}
