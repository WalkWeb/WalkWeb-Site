<?php

declare(strict_types=1);

namespace App\Domain\Account\Character;

use App\Domain\Account\Character\Genesis\GenesisInterface;
use App\Domain\Account\Character\Profession\ProfessionInterface;
use App\Domain\Account\Character\Season\SeasonInterface;
use App\Domain\Account\Floor\FloorInterface;
use App\Domain\Account\Character\Level\LevelInterface;

class Character implements CharacterInterface
{
    private string $id;
    private string $accountId;
    private string $characterMainId;
    private string $avatar;
    private SeasonInterface $season;
    private GenesisInterface $genesis;
    private ProfessionInterface $profession;
    private FloorInterface $floor;
    private LevelInterface $level;

    public function __construct(
        string $id,
        string $accountId,
        string $characterMainId,
        string $avatar,
        SeasonInterface $season,
        GenesisInterface $genesis,
        ProfessionInterface $profession,
        FloorInterface $floor,
        LevelInterface $level
    ) {
        $this->id = $id;
        $this->accountId = $accountId;
        $this->characterMainId = $characterMainId;
        $this->avatar = $avatar;
        $this->season = $season;
        $this->genesis = $genesis;
        $this->profession = $profession;
        $this->floor = $floor;
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
    public function getAccountId(): string
    {
        return $this->accountId;
    }

    /**
     * @return string
     */
    public function getMainCharacterId(): string
    {
        return $this->characterMainId;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @return SeasonInterface
     */
    public function getSeason(): SeasonInterface
    {
        return $this->season;
    }

    /**
     * @return GenesisInterface
     */
    public function getGenesis(): GenesisInterface
    {
        return $this->genesis;
    }

    /**
     * @return ProfessionInterface
     */
    public function getProfession(): ProfessionInterface
    {
        return $this->profession;
    }

    /**
     * @return FloorInterface
     */
    public function getFloor(): FloorInterface
    {
        return $this->floor;
    }

    /**
     * @return LevelInterface
     */
    public function getLevel(): LevelInterface
    {
        return $this->level;
    }
}
