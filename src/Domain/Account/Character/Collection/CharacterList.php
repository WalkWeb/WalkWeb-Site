<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Collection;

use App\Domain\Account\Floor\FloorInterface;

class CharacterList implements CharacterListInterface
{
    private string $id;
    private string $avatar;
    private string $professionNameMale;
    private string $professionNameFemale;
    private string $genesis;
    private int $floorId;
    private int $level;

    public function __construct(string $id, string $avatar, string $professionNameMale, string $professionNameFemale, string $genesis, int $floorId, int $level)
    {
        $this->id = $id;
        $this->avatar = $avatar;
        $this->professionNameMale = $professionNameMale;
        $this->professionNameFemale = $professionNameFemale;
        $this->genesis = $genesis;
        $this->floorId = $floorId;
        $this->level = $level;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @return string
     */
    public function getProfession(): string
    {
        if ($this->floorId === FloorInterface::MALE) {
            return $this->professionNameMale;
        }


        return $this->professionNameFemale;
    }

    /**
     * @return string
     */
    public function getGenesis(): string
    {
        return $this->genesis;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }
}
