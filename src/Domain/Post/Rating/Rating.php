<?php

declare(strict_types=1);

namespace App\Domain\Post\Rating;

class Rating implements RatingInterface
{
    private int $rating;
    private int $likes;
    private int $dislikes;
    private int $userReaction;

    public function __construct(int $likes, int $dislikes, int $userReaction)
    {
        $this->rating = $likes - $dislikes;
        $this->likes = $likes;
        $this->dislikes = $dislikes;
        $this->userReaction = $userReaction;
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @return int
     */
    public function getLikes(): int
    {
        return $this->likes;
    }

    /**
     * @return int
     */
    public function getDislikes(): int
    {
        return $this->dislikes;
    }

    /**
     * @return string
     */
    public function getColorClass(): string
    {
        if ($this->rating === 0) {
            return self::DEFAULT_CLASS_COLOR;
        }

        if ($this->rating > 0) {
            return self::POSITIVE_CLASS_COLOR;
        }

        return self::NEGATIVE_CLASS_COLOR;
    }

    /**
     * @return bool
     */
    public function existUserReaction(): bool
    {
        return $this->userReaction !== 0;
    }

    /**
     * @return int
     */
    public function getUserReaction(): int
    {
        return $this->userReaction;
    }
}
