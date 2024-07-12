<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\Account\Energy\EnergyInterface;
use App\Domain\Account\Group\AccountGroupInterface;
use App\Domain\Account\MainCharacter\Level\LevelInterface;
use App\Domain\Account\Notice\NoticeCollection;
use App\Domain\Account\Status\AccountStatusInterface;

class Auth implements AuthInterface
{
    private string $id;
    private string $name;
    private string $avatar;
    private AccountGroupInterface $group;
    private AccountStatusInterface $status;
    private EnergyInterface $energy;
    private bool $canLike;
    private NoticeCollection $notices;
    private LevelInterface $level;
    private int $statPoints;
    private string $template;
    private bool $emailVerified;

    public function __construct(
        string $id,
        string $name,
        string $avatar,
        AccountGroupInterface $group,
        AccountStatusInterface $status,
        EnergyInterface $energy,
        bool $canLike,
        NoticeCollection $notices,
        LevelInterface $level,
        int $statPoints,
        string $template,
        bool $emailVerified
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->avatar = $avatar;
        $this->group = $group;
        $this->status = $status;
        $this->energy = $energy;
        $this->canLike = $canLike;
        $this->notices = $notices;
        $this->level = $level;
        $this->statPoints = $statPoints;
        $this->template = $template;
        $this->emailVerified = $emailVerified;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
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
     * @return EnergyInterface
     */
    public function getEnergy(): EnergyInterface
    {
        return $this->energy;
    }

    /**
     * @return bool
     */
    public function isCanLike(): bool
    {
        return $this->canLike;
    }

    /**
     * @return NoticeCollection
     */
    public function getNotices(): NoticeCollection
    {
        return $this->notices;
    }

    /**
     * @return LevelInterface
     */
    public function getLevel(): LevelInterface
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getStatPoints(): int
    {
        return $this->statPoints;
    }

    /**
     * @param int $statPoints
     */
    public function setStatPoints(int $statPoints): void
    {
        $this->statPoints = $statPoints;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @return bool
     */
    public function isEmailVerified(): bool
    {
        return $this->emailVerified;
    }
}
