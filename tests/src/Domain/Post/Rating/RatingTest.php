<?php

declare(strict_types=1);

namespace Test\src\Domain\Post\Rating;

use App\Domain\Post\Rating\Rating;
use App\Domain\Post\Rating\RatingInterface;
use Test\AbstractTest;

class RatingTest extends AbstractTest
{
    /**
     * Тест на успешное создание объекта Rating
     *
     * @dataProvider createDataProvider
     * @param int $likes
     * @param int $dislikes
     * @param int $userReaction
     * @param string $expectedColorClass
     */
    public function testRatingCreate(
        int $likes,
        int $dislikes,
        int $userReaction,
        string $expectedColorClass
    ): void
    {
        $rating = new Rating($likes, $dislikes, $userReaction);

        self::assertEquals($likes - $dislikes, $rating->getRating());
        self::assertEquals($likes, $rating->getLikes());
        self::assertEquals($dislikes, $rating->getDislikes());
        self::assertEquals($expectedColorClass, $rating->getColorClass());
        self::assertEquals($userReaction, $rating->getUserReaction());
        self::assertEquals($userReaction !== 0, $rating->existUserReaction());
    }

    /**
     * @return array
     */
    public function createDataProvider(): array
    {
        return [
            [
                0,
                0,
                0,
                RatingInterface::DEFAULT_CLASS_COLOR
            ],
            [
                15,
                5,
                1,
                RatingInterface::POSITIVE_CLASS_COLOR
            ],
            [
                20,
                100,
                -1,
                RatingInterface::NEGATIVE_CLASS_COLOR
            ],
        ];
    }
}
