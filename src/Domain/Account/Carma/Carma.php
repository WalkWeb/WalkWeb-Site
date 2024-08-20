<?php

declare(strict_types=1);

namespace App\Domain\Account\Carma;

use App\Domain\Account\Character\Season\Season;

class Carma implements CarmaInterface
{
    private string $id;
    private string $accountId;
    private Season $season;
    private int $carma;
    private int $uses;

    public function __construct(string $id, string $accountId, Season $season, int $carma, int $uses)
    {
        $this->id = $id;
        $this->accountId = $accountId;
        $this->season = $season;
        $this->carma = $carma;
        $this->uses = $uses;
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
     * @return Season
     */
    public function getSeason(): Season
    {
        return $this->season;
    }

    /**
     * @return int
     */
    public function getCarma(): int
    {
        return $this->carma;
    }

    /**
     * @return int
     */
    public function getUses(): int
    {
        return $this->uses;
    }

    /**
     * @return int
     */
    public function getAvailable(): int
    {
        return $this->carma - $this->uses;
    }
}
