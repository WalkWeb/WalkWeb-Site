<?php

declare(strict_types=1);

namespace App\Domain\Account\Energy;

interface EnergyInterface
{
    public const BASE_ENERGY = 150;

    // Количество секунд, необходимых для восстановления 1 энергии
    public const RESTORE = 60;

    public const SUCCESS_ADDED   = 'Energy success added';
    public const SUCCESS_REDUCED = 'Energy success reduced';

    public const ID_MIN_LENGTH = 5;
    public const ID_MAX_LENGTH = 36;

    public const MIN_ENERGY    = 0;
    public const MAX_ENERGY    = 2000;

    public const MIN_BONUS     = 0;
    public const MAX_BONUS     = 1000;

    /**
     * id записи в таблице energy
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Текущая энергия
     *
     * @return int
     */
    public function getEnergy(): int;

    /**
     * Максимальная энергия
     *
     * @return int
     */
    public function getMaxEnergy(): int;

    /**
     * Текущее время
     *
     * @return float
     */
    public function getTime(): float;

    /**
     * Время последнего обновления данных об энергии в базе
     *
     * @return float
     */
    public function getUpdatedAt(): float;

    /**
     * Остаток в секундах (необходим для подсчета оставшегося времени до увеличения энергии)
     *
     * @return int
     */
    public function getResidue(): int;

    /**
     * Обрабатывает запрос на изменение энергии
     *
     * @param int $value
     * @return string
     */
    public function editEnergy(int $value): string;

    /**
     * Возвращает заполненность полоски энергии в %
     *
     * @return int
     */
    public function getEnergyWeight(): int;

    /**
     * Возвращает заполненность полоски до следующего восстановления энергии в %
     *
     * @return int
     */
    public function getRestoreWeight(): int;

    /**
     * Была ли энергия изменена, если да - необходимо обновить данные в базе
     *
     * @return bool
     */
    public function isUpdated(): bool;
}
