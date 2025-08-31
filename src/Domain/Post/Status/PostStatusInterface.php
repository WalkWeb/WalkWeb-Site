<?php

declare(strict_types=1);

namespace App\Domain\Post\Status;

interface PostStatusInterface
{
    public const int DEFAULT = 1;
    public const int SILVER  = 2;
    public const int GOLD    = 3;
    public const int DIAMOND = 4;

    public const int RATING_SILVER  = 3;
    public const int RATING_GOLD    = 6;
    public const int RATING_DIAMOND = 12;

    public const int EXP_SILVER  = 200;
    public const int EXP_GOLD    = 500;
    public const int EXP_DIAMOND = 1000;

    /**
     * Возвращает ID статуса поста
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Возвращает название статус поста (обычный, серебряный, золотой, брильянтовый)
     *
     * @return string
     */
    public function getName(): string;
}
