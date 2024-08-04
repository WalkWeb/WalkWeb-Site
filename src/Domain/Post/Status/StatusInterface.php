<?php

declare(strict_types=1);

namespace App\Domain\Post\Status;

interface StatusInterface
{
    public const DEFAULT = 1;
    public const SILVER  = 2;
    public const GOLD    = 3;
    public const DIAMOND = 4;

    public const RATING_SILVER  = 3;
    public const RATING_GOLD    = 6;
    public const RATING_DIAMOND = 12;

    public const EXP_SILVER  = 200;
    public const EXP_GOLD    = 500;
    public const EXP_DIAMOND = 1000;

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
