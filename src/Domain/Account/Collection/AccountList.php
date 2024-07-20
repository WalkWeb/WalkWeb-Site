<?php

declare(strict_types=1);

namespace App\Domain\Account\Collection;

use App\Domain\Account\Group\AccountGroupInterface;
use App\Domain\Account\Status\AccountStatusInterface;

/**
 * Объект для вывода списка аккаунтов - минимальный набор данных
 */
class AccountList implements AccountListInterface
{
    private string $id;
    private string $avatar;
    private string $name;
    private int $level;
    private int $exp;
    private AccountStatusInterface $status;
    private AccountGroupInterface $group;
    //private int $postCount;
    //private int $commentCount;
    private int $carma;

    public function __construct(
        string $id,
        string $avatar,
        string $name,
        int $level,
        int $exp,
        AccountStatusInterface $status,
        AccountGroupInterface $group,
        int $carma
    )
    {
        $this->id = $id;
        $this->avatar = $avatar;
        $this->name = $name;
        $this->level = $level;
        $this->exp = $exp;
        $this->status = $status;
        $this->group = $group;
        $this->carma = $carma;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getExp(): int
    {
        return $this->exp;
    }

    /**
     * @return AccountGroupInterface
     */
    public function getGroup(): AccountGroupInterface
    {
        return $this->group;
    }

    /**
     * @return AccountStatusInterface
     */
    public function getStatus(): AccountStatusInterface
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getCarma(): int
    {
        return $this->carma;
    }
}
