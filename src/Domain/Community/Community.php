<?php

declare(strict_types=1);

namespace App\Domain\Community;

use DateTimeInterface;

class Community implements CommunityInterface
{
    private string $id;
    private int $level;
    private string $name;
    private string $slug;
    private string $description;
    private string $icon;
    private string $iconSmall;
    private string $headImage;
    private int $followers;
    private ?string $fixedPostId;
    private ?string $menu;
    private string $ownerId;
    private int $totalPostCount;
    private int $silverPostCount;
    private int $goldPostCount;
    private int $diamondPostCount;
    private int $totalCommentCount;
    private bool $isJoined;
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct(
        string $id,
        int $level,
        string $name,
        string $slug,
        string $description,
        string $icon,
        string $iconSmall,
        string $headImage,
        int $followers,
        ?string $fixedPostId,
        ?string $menu,
        string $ownerId,
        int $totalPostCount,
        int $silverPostCount,
        int $goldPostCount,
        int $diamondPostCount,
        int $totalCommentCount,
        bool $isJoined,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt
    ) {
        $this->id = $id;
        $this->level = $level;
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
        $this->icon = $icon;
        $this->iconSmall = $iconSmall;
        $this->headImage = $headImage;
        $this->followers = $followers;
        $this->fixedPostId = $fixedPostId;
        $this->menu = $menu;
        $this->ownerId = $ownerId;
        $this->totalPostCount = $totalPostCount;
        $this->silverPostCount = $silverPostCount;
        $this->goldPostCount = $goldPostCount;
        $this->diamondPostCount = $diamondPostCount;
        $this->totalCommentCount = $totalCommentCount;
        $this->isJoined = $isJoined;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getIconSmall(): string
    {
        return $this->iconSmall;
    }

    /**
     * @return string
     */
    public function getHeadImage(): string
    {
        return $this->headImage;
    }

    /**
     * @return int
     */
    public function getFollowers(): int
    {
        return $this->followers;
    }

    /**
     * @return string
     */
    public function getFixedPostId(): ?string
    {
        return $this->fixedPostId;
    }

    /**
     * @return string
     */
    public function getMenu(): ?string
    {
        return $this->menu;
    }

    /**
     * @return string
     */
    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    /**
     * @return int
     */
    public function getTotalPostCount(): int
    {
        return $this->totalPostCount;
    }

    /**
     * @return int
     */
    public function getSilverPostCount(): int
    {
        return $this->silverPostCount;
    }

    /**
     * @return int
     */
    public function getGoldPostCount(): int
    {
        return $this->goldPostCount;
    }

    /**
     * @return int
     */
    public function getDiamondPostCount(): int
    {
        return $this->diamondPostCount;
    }

    /**
     * @return int
     */
    public function getTotalCommentCount(): int
    {
        return $this->totalCommentCount;
    }

    /**
     * @return bool
     */
    public function isJoined(): bool
    {
        return $this->isJoined;
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
