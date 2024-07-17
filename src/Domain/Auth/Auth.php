<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\Account\Energy\EnergyInterface;
use App\Domain\Account\Group\AccountGroupInterface;
use App\Domain\Account\MainCharacter\Level\LevelInterface;
use App\Domain\Account\Notice\NoticeCollection;
use App\Domain\Account\Status\AccountStatusInterface;
use App\Domain\Account\Upload\UploadInterface;

class Auth implements AuthInterface
{
    private string $id;
    private string $name;
    private string $avatar;
    private string $verifiedToken;
    private AccountGroupInterface $group;
    private AccountStatusInterface $status;
    private EnergyInterface $energy;
    private bool $canLike;
    private NoticeCollection $notices;
    private LevelInterface $level;
    private string $template;
    private bool $emailVerified;
    private UploadInterface $upload;

    public function __construct(
        string $id,
        string $name,
        string $avatar,
        string $verifiedToken,
        AccountGroupInterface $group,
        AccountStatusInterface $status,
        EnergyInterface $energy,
        bool $canLike,
        NoticeCollection $notices,
        LevelInterface $level,
        string $template,
        bool $emailVerified,
        UploadInterface $upload
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->avatar = $avatar;
        $this->verifiedToken = $verifiedToken;
        $this->group = $group;
        $this->status = $status;
        $this->energy = $energy;
        $this->canLike = $canLike;
        $this->notices = $notices;
        $this->level = $level;
        $this->template = $template;
        $this->emailVerified = $emailVerified;
        $this->upload = $upload;
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
     * @return string
     */
    public function getVerifiedToken(): string
    {
        return $this->verifiedToken;
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

    /**
     * @return UploadInterface
     */
    public function getUpload(): UploadInterface
    {
        return $this->upload;
    }
}
