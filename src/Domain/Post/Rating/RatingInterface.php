<?php

declare(strict_types=1);

namespace App\Domain\Post\Rating;

// TODO Подумать на тему того, сделать ли этот объект общим для постов и комментариев

interface RatingInterface
{
    public const DEFAULT_CLASS_COLOR  = 'defaultRatingColor';
    public const POSITIVE_CLASS_COLOR = 'positiveRatingColor';
    public const NEGATIVE_CLASS_COLOR = 'negativeRatingColor';

    /**
     * Возвращает суммарный рейтинг поста
     *
     * @return int
     */
    public function getRating(): int;

    /**
     * Возвращает суммарное количество лайков поста
     *
     * @return int
     */
    public function getLikes(): int;

    /**
     * Возвращает суммарное количество дизлайков поста
     *
     * @return int
     */
    public function getDislikes(): int;

    /**
     * Возвращает класс цвета рейтинга. Например положительный рейтинг будет зеленым, а отрицательный красным.
     *
     * При этом конкретные цвета могут отличаться в разных шаблонах сайта
     *
     * @return string
     */
    public function getColorClass(): string;

    /**
     * Была ли реакция (лайк/дизлайк) авторизованного пользователя на этот пост
     *
     * Если пользователь не авторизован - будет false
     *
     * @return bool
     */
    public function existUserReaction(): bool;

    /**
     * Возвращает реакцию авторизованного пользователя. Если был лайк - вернет +1 или больше (планируются механики,
     * когда рейтинг может измениться сильнее, чем на 1), если был дизлайк - вернет -1 или менее. Если вернуло 0 -
     * значит реакции не было, или пользователь не авторизован
     *
     * @return int
     */
    public function getUserReaction(): int;
}
