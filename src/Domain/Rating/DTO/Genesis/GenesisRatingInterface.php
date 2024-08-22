<?php

declare(strict_types=1);

namespace App\Domain\Rating\DTO\Genesis;

interface GenesisRatingInterface
{
    public function getId(): int;
    public function getIcon(): string;
    public function getName(): string;
    public function getMemberCount(): int;
    public function getPostCount(): int;
    public function getCommentCount(): int;
    public function getCarmaCount(): int;
}
