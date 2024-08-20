<?php

declare(strict_types=1);

namespace App\Domain\Account\Collection;

use App\Domain\Account\Group\AccountGroupInterface;
use App\Domain\Account\Status\AccountStatusInterface;

interface AccountListInterface
{
    public function getId(): string;
    public function getAvatar(): string;
    public function getName(): string;
    public function getLevel(): int;
    public function getExp(): int;
    public function getGroup(): AccountGroupInterface;
    public function getStatus(): AccountStatusInterface;
    public function getCarma(): int;
    public function getCarmaSign(): string;
    public function getCarmaColoClass(): string;
}
