<?php

declare(strict_types=1);

namespace App\Domain\Account;

use App\Domain\Account\Floor\FloorInterface;
use App\Domain\Account\Group\AccountGroupInterface;
use App\Domain\Account\MainCharacter\MainCharacterInterface;
use App\Domain\Account\Status\AccountStatus;
use App\Domain\Account\Upload\AccountUpload;
use DateTimeInterface;
use WalkWeb\NW\AppException;

class Account implements AccountInterface
{
    private string $id;
    private string $login;
    private string $name;
    private string $password;
    private string $email;
    private bool $emailVerified;
    private bool $regComplete;
    private string $authToken;
    private string $verifiedToken;
    private string $template;
    private string $ip;
    private string $ref;
    private string $userAgent;
    private bool $canLike;
    private ?MainCharacterInterface $mainCharacter;
    private FloorInterface $floor;
    private AccountStatus $status;
    private AccountGroupInterface $group;
    private AccountUpload $upload;
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct(
        string $id,
        string $login,
        string $name,
        string $password,
        string $email,
        bool $emailVerified,
        bool $regComplete,
        string $authToken,
        string $verifiedToken,
        string $template,
        string $ip,
        string $ref,
        string $userAgent,
        bool $canLike,
        FloorInterface $floor,
        AccountStatus $status,
        AccountGroupInterface $group,
        AccountUpload $upload,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt,
        ?MainCharacterInterface $mainCharacter
    )
    {
        $this->id = $id;
        $this->login = $login;
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
        $this->emailVerified = $emailVerified;
        $this->regComplete = $regComplete;
        $this->authToken = $authToken;
        $this->verifiedToken = $verifiedToken;
        $this->template = $template;
        $this->ip = $ip;
        $this->ref = $ref;
        $this->userAgent = $userAgent;
        $this->canLike = $canLike;
        $this->floor = $floor;
        $this->status = $status;
        $this->group = $group;
        $this->upload = $upload;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->mainCharacter = $mainCharacter;
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
    public function getLogin(): string
    {
        return $this->login;
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
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return bool
     */
    public function isRegComplete(): bool
    {
        return $this->regComplete;
    }

    /**
     * @return bool
     */
    public function isEmailVerified(): bool
    {
        return $this->emailVerified;
    }

    // TODO delete
    public function emailVerified(): void
    {
        $this->emailVerified = true;
        // Помимо подтверждения email могут быть другие необходимые действия для завершения регистрации
        // Но в текущей простой версии подтверждении email автоматически завершает регистрацию
        $this->regComplete = true;
    }

    /**
     * @return string
     */
    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    /**
     * @return string
     */
    public function getVerifiedToken(): string
    {
        return $this->verifiedToken;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @return string
     */
    public function getRef(): string
    {
        return $this->ref;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @return bool
     */
    public function isCanLike(): bool
    {
        return $this->canLike;
    }

    /**
     * @return MainCharacterInterface
     * @throws AppException
     */
    public function getMainCharacter(): MainCharacterInterface
    {
        if ($this->mainCharacter === null) {
            throw new AppException(AccountException::MISS_MAIN_CHARACTER);
        }

        return $this->mainCharacter;
    }

    /**
     * @return FloorInterface
     */
    public function getFloor(): FloorInterface
    {
        return $this->floor;
    }

    /**
     * @return AccountStatus
     */
    public function getStatus(): AccountStatus
    {
        return $this->status;
    }

    /**
     * @return AccountGroupInterface
     */
    public function getGroup(): AccountGroupInterface
    {
        return $this->group;
    }

    /**
     * @return AccountUpload
     */
    public function getUpload(): AccountUpload
    {
        return $this->upload;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }
}
