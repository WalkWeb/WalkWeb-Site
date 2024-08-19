<?php

declare(strict_types=1);

namespace App\Domain\Comment;

use App\Domain\Post\Rating\RatingInterface;
use DateTimeInterface;

interface CommentInterface
{
    public const CREATE_ENERGY_COST = 5;
    public const CREATE_EXP         = 5;

    public const COMMENT_MIN_LENGTH = 1;
    public const COMMENT_MAX_LENGTH = 2000;

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
    public function isLiked(): bool;
    public function getRating(): RatingInterface;
    public function getCreatedAt(): DateTimeInterface;
    public function getUpdatedAt(): DateTimeInterface;
}
