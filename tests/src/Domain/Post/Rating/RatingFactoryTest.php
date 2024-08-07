<?php

declare(strict_types=1);

namespace Test\src\Domain\Post\Rating;

use App\Domain\Post\Rating\RatingException;
use App\Domain\Post\Rating\RatingFactory;
use App\Domain\Post\Rating\RatingInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class RatingFactoryTest extends AbstractTest
{
    /**
     * Тест на успешное создание объекта Rating на основе массива параметров
     *
     * @dataProvider successDataProvider
     * @param array $data
     * @param string $expectedClassRating
     * @throws AppException
     */
    public function testRatingFactoryCreateSuccess(array $data, string $expectedClassRating): void
    {
        $rating = $this->getFactory()->create($data);

        self::assertEquals($data['likes'] - $data['dislikes'], $rating->getRating());
        self::assertEquals($data['likes'], $rating->getLikes());
        self::assertEquals($data['dislikes'], $rating->getDislikes());
        self::assertEquals($data['user_reaction'], $rating->getUserReaction());
        self::assertEquals($expectedClassRating, $rating->getColorClass());
        self::assertEquals($data['user_reaction'] !== 0, $rating->existUserReaction());
    }

    /**
     * Тест на различные варианты невалидных данных
     *
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     */
    public function testRatingFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        $this->getFactory()->create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                ],
                RatingInterface::DEFAULT_CLASS_COLOR,
            ],
            [
                [
                    'likes'         => 15,
                    'dislikes'      => 5,
                    'user_reaction' => 1,
                ],
                RatingInterface::POSITIVE_CLASS_COLOR,
            ],
            [
                [
                    'likes'         => 20,
                    'dislikes'      => 100,
                    'user_reaction' => -1,
                ],
                RatingInterface::NEGATIVE_CLASS_COLOR,
            ],
        ];
    }

    /**
     * @return array
     */
    public function failDataProvider(): array
    {
        return [
            // Отсутствует likes
            [
                [
                    'rating'        => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                ],
                RatingException::INVALID_LIKES,
            ],
            // likes некорректного типа
            [
                [
                    'rating'        => 0,
                    'likes'         => null,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                ],
                RatingException::INVALID_LIKES,
            ],
            // Отсутствует dislikes
            [
                [
                    'rating'        => 0,
                    'likes'         => 0,
                    'user_reaction' => 0,
                ],
                RatingException::INVALID_DISLIKES,
            ],
            // dislikes некорректного типа
            [
                [
                    'rating'        => 0,
                    'likes'         => 0,
                    'dislikes'      => 0.0,
                    'user_reaction' => 0,
                ],
                RatingException::INVALID_DISLIKES,
            ],
            // отсутствует user_reaction
            [
                [
                    'likes'         => 0,
                    'dislikes'      => 0,
                ],
                RatingException::INVALID_USER_REACTION,
            ],
            // user_reaction некорректного типа
            [
                [
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => null,
                ],
                RatingException::INVALID_USER_REACTION,
            ],
        ];
    }

    /**
     * @return RatingFactory
     */
    private function getFactory(): RatingFactory
    {
        return new RatingFactory();
    }
}
