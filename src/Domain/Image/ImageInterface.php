<?php

declare(strict_types=1);

namespace App\Domain\Image;

use DateTimeInterface;

interface ImageInterface
{
    // TODO min-max value

    public function getId(): string;
    public function getAccountId(): string;
    public function getName(): string;
    public function getFilePath(): string;
    public function getSize(): int;
    public function getWidth(): int;
    public function getHeight(): int;
    public function getCreatedAt(): DateTimeInterface;
}
