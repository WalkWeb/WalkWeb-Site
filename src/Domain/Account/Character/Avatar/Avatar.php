<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Avatar;

use App\Domain\Account\Character\Genesis\GenesisInterface;
use App\Domain\Account\Floor\FloorInterface;

class Avatar implements AvatarInterface
{
    private int $id;
    private GenesisInterface $genesis;
    private FloorInterface $floor;
    private string $originUrl;
    private string $smallUrl;

    public function __construct(int $id, GenesisInterface $genesis, FloorInterface $floor, string $originUrl, string $smallUrl)
    {
        $this->id = $id;
        $this->genesis = $genesis;
        $this->floor = $floor;
        $this->originUrl = $originUrl;
        $this->smallUrl = $smallUrl;
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
     * @return FloorInterface
     */
    public function getFloor(): FloorInterface
    {
        return $this->floor;
    }

    /**
     * @return string
     */
    public function getOriginUrl(): string
    {
        return $this->originUrl;
    }

    /**
     * @return string
     */
    public function getSmallUrl(): string
    {
        return $this->smallUrl;
    }
}
