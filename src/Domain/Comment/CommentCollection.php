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
     * @var CommentInterface[]
     */
    private array $added = [];

    /**
     * @param CommentInterface $comment
     * @throws AppException
     */
    public function add(CommentInterface $comment): void
    {
        if (array_key_exists($comment->getId(), $this->elements)) {
            throw new AppException(CommentException::ALREADY_EXIST);
        }

        if ($comment->getParentId() && array_key_exists($comment->getParentId(), $this->added)) {
            $this->added[$comment->getParentId()]->addChildren($comment);
            $this->added[$comment->getId()] = $comment;
        } else {
            $this->elements[$comment->getId()] = $comment;
            $this->added[$comment->getId()] = $comment;
        }
    }

    public function current(): CommentInterface
    {
        return current($this->elements);
    }
}
