<?php

declare(strict_types=1);

namespace App\Domain\Comment;

use Countable;
use Iterator;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\CollectionTrait;

class CommentCollection implements Iterator, Countable
{
    use CollectionTrait;

    /**
     * @var CommentInterface[]
     */
    private array $elements = [];

    /**
     * @param CommentInterface $post
     * @throws AppException
     */
    public function add(CommentInterface $post): void
    {
        if (array_key_exists($post->getId(), $this->elements)) {
            throw new AppException(CommentException::ALREADY_EXIST);
        }

        $this->elements[$post->getId()] = $post;
    }

    public function current(): CommentInterface
    {
        return current($this->elements);
    }
}
