<?php

declare(strict_types=1);

namespace App\Domain\Post\Collection;

use App\Domain\Post\Rating\RatingInterface;
use DateTimeInterface;

class PostList implements PostListInterface
{
    private string $id;
    private string $title;
    private string $slug;
    private string $htmlContent;
    private string $authorName;
    private RatingInterface $rating;
    private int $commentCount;
    private array $tags;
    private bool $isLiked;
    private string $communitySlug;
    private string $communityName;
    private DateTimeInterface $createdAt;

    public function __construct(
        string $id,
        string $title,
        string $slug,
        string $htmlContent,
        string $authorName,
        RatingInterface $rating,
        int $commentCount,
        array $tags,
        bool $isLiked,
        string $communitySlug,
        string $communityName,
        DateTimeInterface $createdAt
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->htmlContent = $htmlContent;
        $this->authorName = $authorName;
        $this->rating = $rating;
        $this->commentCount = $commentCount;
        $this->tags = $tags;
        $this->isLiked = $isLiked;
        $this->communitySlug = $communitySlug;
        $this->communityName = $communityName;
        $this->createdAt = $createdAt;
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getHtmlContent(): string
    {
        return $this->htmlContent;
    }

    /**
     * @return string
     */
    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    /**
     * @return RatingInterface
     */
    public function getRating(): RatingInterface
    {
        return $this->rating;
    }

    /**
     * @return int
     */
    public function getCommentCount(): int
    {
        return $this->commentCount;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return bool
     */
    public function isLiked(): bool
    {
        return $this->isLiked;
    }

    /**
     * @return string
     */
    public function getCommunitySlug(): string
    {
        return $this->communitySlug;
    }

    /**
     * @return string
     */
    public function getCommunityName(): string
    {
        return $this->communityName;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
