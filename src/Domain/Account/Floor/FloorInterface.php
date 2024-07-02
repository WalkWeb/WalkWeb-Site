<?php

declare(strict_types=1);

namespace App\Domain\Account\Floor;

interface FloorInterface
{
    public const MALE   = 1;
    public const FEMALE = 2;

    /**
     * Возвращает ID пола аккаунта
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Возвращает пол аккаунта
     *
     * @return string
     */
    public function getName(): string;
}
