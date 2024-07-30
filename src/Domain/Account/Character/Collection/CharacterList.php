<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Collection;

class CharacterList implements CharacterListInterface
{
    private string $id;
    private string $avatar;
    private string $profession;
    private string $genesis;
    private int $level;

    public function __construct(string $id, string $avatar, string $profession, string $genesis, int $level)
    {
        $this->id = $id;
        $this->avatar = $avatar;
        $this->profession = $profession;
        $this->genesis = $genesis;
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
        return $this->profession;
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
