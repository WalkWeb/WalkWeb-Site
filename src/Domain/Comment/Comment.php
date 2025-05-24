<?php

declare(strict_types=1);

namespace App\Domain\Comment;

use App\Domain\Post\Rating\RatingInterface;
use DateTimeInterface;
use WalkWeb\NW\AppException;

class Comment implements CommentInterface
{
    private string $id;
    private string $postId;
    private ?string $authorId;
    private string $authorName;
    private string $authorAvatar;
    private int $authorLevel;
    private string $message;
    private bool $approved;
    private ?string $parentId;
    private CommentCollection $children;
    private int $level;
    private bool $isLiked;
    private RatingInterface $rating;
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct(
        string $id,
        string $postId,
        ?string $authorId,
        string $authorName,
        string $authorAvatar,
        int $authorLevel,
        string $message,
        bool $approved,
        ?string $parentId,
        int $level,
        bool $isLiked,
        RatingInterface $rating,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt
    ) {
        $this->id = $id;
        $this->postId = $postId;
        $this->authorId = $authorId;
        $this->authorName = $authorName;
        $this->authorAvatar = $authorAvatar;
        $this->authorLevel = $authorLevel;
        $this->message = $message;
        $this->approved = $approved;
        $this->parentId = $parentId;
        $this->level = $level;
        $this->isLiked = $isLiked;
        $this->rating = $rating;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->children = new CommentCollection();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPostId(): string
    {
        return $this->postId;
    }

    /**
     * @return string|null
     */
    public function getAuthorId(): ?string
    {
        return $this->authorId;
    }

    /**
     * @return string
     */
    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    /**
     * @return string
     */
    public function getAuthorAvatar(): string
    {
        return $this->authorAvatar;
    }

    /**
     * @return int
     */
    public function getAuthorLevel(): int
    {
        return $this->authorLevel;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->approved;
    }

    /**
     * @return string|null
     */
    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    /**
     * @param CommentInterface $comment
     * @throws AppException
     */
    public function addChildren(CommentInterface $comment): void
    {
        $this->children->add($comment);
    }

    /**
     * @return CommentCollection
     */
    public function getChildren(): CommentCollection
    {
        return $this->children;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return bool
     */
    public function isLiked(): bool
    {
        return $this->isLiked;
    }

    /**
     * @return RatingInterface
     */
    public function getRating(): RatingInterface
    {
        return $this->rating;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }
}
