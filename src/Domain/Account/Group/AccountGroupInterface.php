<?php

declare(strict_types=1);

namespace App\Domain\Account\Group;

interface AccountGroupInterface
{
    public const USER       = 10;
    public const MODERATOR  = 20;
    public const ADMIN      = 31;
    public const MAIN_ADMIN = 30;

    /**
     * Возвращает ID группы аккаунта
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Возвращает группу аккаунта
     *
     * @return string
     */
    public function getName(): string;
}
