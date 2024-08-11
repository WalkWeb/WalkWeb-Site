<?php

declare(strict_types=1);

namespace App\Domain\Post\Collection;

use App\Domain\Post\Rating\RatingInterface;
use DateTimeInterface;

interface PostListInterface
{
    public function getId(): string;
    public function getTitle(): string;
    public function getSlug(): string;
    public function getHtmlContent(): string;
    public function getAuthorName(): string;
    public function getRating(): RatingInterface;
    public function getCommentCount(): int;
    public function getTags(): array;
    public function isLiked(): bool;
    public function getCreatedAt(): DateTimeInterface;
}
