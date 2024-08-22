<?php

declare(strict_types=1);

namespace App\Domain\Rating\DTO\Genesis;

class GenesisRating implements GenesisRatingInterface
{
    private int $id;
    private string $icon;
    private string $name;
    private int $memberCount;
    private int $postCount;
    private int $commentCount;
    private int $carmaCount;

    public function __construct(int $id, string $icon, string $name, int $memberCount, int $postCount, int $commentCount, int $carmaCount)
    {
        $this->id = $id;
        $this->icon = $icon;
        $this->name = $name;
        $this->memberCount = $memberCount;
        $this->postCount = $postCount;
        $this->commentCount = $commentCount;
        $this->carmaCount = $carmaCount;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getMemberCount(): int
    {
        return $this->memberCount;
    }

    /**
     * @return int
     */
    public function getPostCount(): int
    {
        return $this->postCount;
    }

    /**
     * @return int
     */
    public function getCommentCount(): int
    {
        return $this->commentCount;
    }

    /**
     * @return int
     */
    public function getCarmaCount(): int
    {
        return $this->carmaCount;
    }
}
