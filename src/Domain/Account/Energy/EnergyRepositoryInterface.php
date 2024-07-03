<?php

declare(strict_types=1);

namespace App\Domain\Account\Energy;

/**
 * Доменная модель ничего не знает и не должна знать о хранилище данных. Здесь представлен лишь требуемый интерфейс для
 * работы. Конкретная реализация должна быть сделана непосредственном в самом проекте, который уже будет знать о базе, в
 * которой будут храниться данные
 *
 * @package Portal\Account\Energy
 */
interface EnergyRepositoryInterface
{
    /**
     * Получает данные из базы и создает объект энергии пользователя
     *
     * @param string $id
     * @return EnergyInterface
     */
    public function get(string $id): EnergyInterface;

    /**
     * Сохраняет обновленные данные по энергии в базе
     *
     * @param EnergyInterface $energy
     */
    public function save(EnergyInterface $energy): void;
}
