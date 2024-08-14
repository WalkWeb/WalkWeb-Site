<?php

declare(strict_types=1);

namespace App\Domain\Comment;

use App\Domain\Post\Rating\RatingInterface;
use DateTimeInterface;

interface CommentInterface
{
    public const DEFAULT_AVATAR = '';

    // TODO min-max value

    public function getId(): string;
    public function getPostId(): string;
    public function getAuthorId(): ?string;
    public function getAuthorName(): string;
    public function getAuthorAvatar(): string;
    public function getAuthorLevel(): int;
    public function getMessage(): string;
    public function isApproved(): bool;
    public function getParentId(): ?string;
    public function getLevel(): int;
    public function getRating(): RatingInterface;
    public function getCreatedAt(): DateTimeInterface;
    public function getUpdatedAt(): DateTimeInterface;
}
