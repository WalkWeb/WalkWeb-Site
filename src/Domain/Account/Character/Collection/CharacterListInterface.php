<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Collection;

interface CharacterListInterface
{
    public function getId(): string;
    public function getAvatar(): string;
    public function getProfession(): string;
    public function getGenesis(): string;
    public function getLevel(): int;
}
