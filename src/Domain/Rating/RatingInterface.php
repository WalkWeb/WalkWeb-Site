<?php

declare(strict_types=1);

namespace App\Domain\Rating;

use App\Domain\Account\Collection\AccountCollection;
use WalkWeb\NW\AppException;

interface RatingInterface
{
    /**
     * Возвращает топ-30 аккаунтов по уровню MainCharacter
     *
     * @throws AppException
     * @return AccountCollection
     */
    public function getTopAccountLevel(): AccountCollection;

    /**
     * Возвращает топ-30 аккаунтов по количеству кармы MainCharacter
     *
     * @throws AppException
     * @return AccountCollection
     */
    public function getTopAccountCarma(): AccountCollection;
}
