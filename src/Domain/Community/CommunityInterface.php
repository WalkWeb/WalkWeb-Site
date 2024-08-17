<?php

declare(strict_types=1);

namespace App\Domain\Community;

use DateTimeInterface;

interface CommunityInterface
{
    public function getId(): string;
    public function getLevel(): int;
    public function getName(): string;
    public function getSlug(): string;
    public function getDescription(): string;
    public function getIcon(): string;
    public function getIconSmall(): string;
    public function getHeadImage(): string;
    public function getFollowers(): int;
    public function getFixedPostId(): ?string;
    public function getMenu(): ?string;
    public function getOwnerId(): string;
    public function getTotalPostCount(): int;
    public function getSilverPostCount(): int;
    public function getGoldPostCount(): int;
    public function getDiamondPostCount(): int;
    public function getTotalCommentCount(): int;
    public function getCreatedAt(): DateTimeInterface;
    public function getUpdatedAt(): DateTimeInterface;
}
