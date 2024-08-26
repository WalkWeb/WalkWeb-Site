<?php

declare(strict_types=1);

namespace App\Domain\Community;

use DateTime;
use DateTimeInterface;

class BlankCommunity implements CommunityInterface
{
    public function getId(): string
    {
        return '';
    }

    public function getLevel(): int
    {
        return 0;
    }

    public function getName(): string
    {
        return '';
    }

    public function getSlug(): string
    {
        return '';
    }

    public function getDescription(): string
    {
        return '';
    }

    public function getIcon(): string
    {
        return '';
    }

    public function getIconSmall(): string
    {
        return '';
    }

    public function getHeadImage(): string
    {
        return '';
    }

    public function getFollowers(): int
    {
        return 0;
    }

    public function getFixedPostId(): ?string
    {
        return null;
    }

    public function getMenu(): ?string
    {
        return null;
    }

    public function getOwnerId(): string
    {
        return '';
    }

    public function getTotalPostCount(): int
    {
        return 0;
    }

    public function getSilverPostCount(): int
    {
        return 0;
    }

    public function getGoldPostCount(): int
    {
        return 0;
    }

    public function getDiamondPostCount(): int
    {
        return 0;
    }

    public function getTotalCommentCount(): int
    {
        return 0;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return new DateTime();
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return new DateTime();
    }
}
