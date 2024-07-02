<?php

declare(strict_types=1);

namespace App\Domain\Account\Status;

interface AccountStatusInterface
{
    public const ACTIVE  = 1;
    public const BLOCKED = 2;

    /**
     * Возвращает ID статуса аккаунта
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Возвращает статус аккаунта
     *
     * @return string
     */
    public function getName(): string;
}
