<?php

declare(strict_types=1);

namespace App\Domain\Account\Energy;

class Energy implements EnergyInterface
{
    private string $id;

    private int $energy;

    private int $maxEnergy;

    private float $time;

    private float $updatedAt;

    private int $residue;

    private bool $updated = false;

    public function __construct(string $id, int $energy, int $maxEnergy, float $time, float $updatedAt, int $residue)
    {
        $this->id = $id;
        $this->energy = $energy;
        $this->maxEnergy = $maxEnergy;
        $this->time = $time;
        $this->updatedAt = $updatedAt;
        $this->residue = $residue;

        // Если энергия меньше максимальной, если больше - просто уменьшаем до максимальной
        if ($this->energy > $this->maxEnergy) {
            $this->energy = $this->maxEnergy;
        }

        // Корректировка residue если больше RESTORE - уменьшаем до RESTORE
        if ($this->residue > self::RESTORE) {
            $this->residue = self::RESTORE;
        }

        // Еще одна корректировка residue: если меньше 0 увеличиваем до 0. Аналогично для ситуации, когда энергия максимальная
        if ($this->residue < 0 || $this->energy === $this->maxEnergy) {
            $this->residue = 0;
        }

        if ($this->energy < $this->maxEnergy) {
            $difference = (int)($this->time - $this->updatedAt) + $this->residue;
            $addEnergy = (int)floor($difference / self::RESTORE);
            $this->residue = (int)floor($difference - ($addEnergy * self::RESTORE));
            $this->addEnergy($addEnergy);
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getEnergy(): int
    {
        return $this->energy;
    }

    /**
     * @return int
     */
    public function getMaxEnergy(): int
    {
        return $this->maxEnergy;
    }

    /**
     * @return float
     */
    public function getTime(): float
    {
        return $this->time;
    }

    /**
     * @return float
     */
    public function getUpdatedAt(): float
    {
        return $this->updatedAt;
    }

    /**
     * @return int
     */
    public function getResidue(): int
    {
        return $this->residue;
    }

    /**
     * @param int $value
     * @return string
     * @throws EnergyException
     */
    public function editEnergy(int $value): string
    {
        if ($value === 0) {
            throw new EnergyException(EnergyException::ZERO_VALUE);
        }

        if ($value > 0) {
            if ($this->energy === $this->maxEnergy) {
                throw new EnergyException(EnergyException::ALREADY_MAX);
            }

            $this->addEnergy($value);
            $this->updated = true;
            return self::SUCCESS_ADDED;
        }

        if (abs($value) > $this->energy) {
            throw new EnergyException(sprintf(EnergyException::NO_ENOUGH, $this->energy, abs($value)));
        }

        $this->addEnergy($value);
        $this->updated = true;
        return self::SUCCESS_REDUCED;
    }

    /**
     * @return int
     */
    public function getEnergyWeight(): int
    {
        return (int)round($this->energy / $this->maxEnergy * 100);
    }

    /**
     * @return int
     */
    public function getRestoreWeight(): int
    {
        return (int)round($this->residue / self::RESTORE * 100);
    }

    /**
     * @return bool
     */
    public function isUpdated(): bool
    {
        return $this->updated;
    }

    /**
     * Добавляет энергию
     *
     * @param int $addEnergy
     */
    private function addEnergy(int $addEnergy): void
    {
        $this->energy += $addEnergy;
        if ($this->energy >= $this->maxEnergy) {
            $this->energy = $this->maxEnergy;
            $this->residue = 0;
        }
    }
}
