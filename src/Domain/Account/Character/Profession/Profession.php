<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Profession;

use App\Domain\Account\Character\Genesis\GenesisInterface;
use App\Domain\Account\Floor\FloorInterface;

class Profession implements ProfessionInterface
{
    private int $id;
    private GenesisInterface $genesis;
    private string $icon;
    private string $nameMale;
    private string $nameFemale;

    public function __construct(int $id, GenesisInterface $genesis, string $icon, string $nameMale, string $nameFemale)
    {
        $this->id = $id;
        $this->genesis = $genesis;
        $this->icon = $icon;
        $this->nameMale = $nameMale;
        $this->nameFemale = $nameFemale;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return GenesisInterface
     */
    public function getGenesis(): GenesisInterface
    {
        return $this->genesis;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param FloorInterface $floor
     * @return string
     */
    public function getName(FloorInterface $floor): string
    {
        if ($floor->getId() === FloorInterface::MALE) {
            return $this->nameMale;
        }

        return $this->nameFemale;
    }

    /**
     * @return string
     */
    public function getNameMale(): string
    {
        return $this->nameMale;
    }

    /**
     * @return string
     */
    public function getNameFemale(): string
    {
        return $this->nameFemale;
    }
}
