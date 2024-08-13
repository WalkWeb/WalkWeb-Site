<?php

declare(strict_types=1);

namespace App\Domain\Image;

use DateTimeInterface;

class Image implements ImageInterface
{
    private string $id;
    private string $accountId;
    private string $name;
    private string $filePath;
    private int $size;
    private int $width;
    private int $height;
    private DateTimeInterface $createdAt;

    public function __construct(
        string $id,
        string $accountId,
        string $name,
        string $filePath,
        int $size,
        int $width,
        int $height,
        DateTimeInterface $createdAt)
    {
        $this->id = $id;
        $this->accountId = $accountId;
        $this->name = $name;
        $this->filePath = $filePath;
        $this->size = $size;
        $this->width = $width;
        $this->height = $height;
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
    public function getAccountId(): string
    {
        return $this->accountId;
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
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
