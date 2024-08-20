<?php

declare(strict_types=1);

namespace App\Domain\Account;

use App\Domain\Account\Carma\CarmaInterface;
use App\Domain\Account\Floor\FloorInterface;
use App\Domain\Account\Group\AccountGroupInterface;
use App\Domain\Account\MainCharacter\MainCharacterInterface;
use App\Domain\Account\Status\AccountStatus;
use App\Domain\Account\Upload\AccountUpload;
use DateTimeInterface;

interface AccountInterface
{
    public const AUTH_TOKEN                = 'auth';

    public const LOGIN_MIN_LENGTH          = 3;
    public const LOGIN_MAX_LENGTH          = 20;
    public const LOGIN_PARENT              = '/^[a-zA-Z0-9а-яА-ЯёЁ\-_]*$/u';

    public const NAME_MIN_LENGTH           = 3;
    public const NAME_MAX_LENGTH           = 20;
    public const NAME_PARENT               = '/^[a-zA-Z0-9а-яА-ЯёЁ\-_]*$/u';

    public const AVATAR_MIN_LENGTH         = 5;
    public const AVATAR_MAX_LENGTH         = 90;

    public const PASSWORD_MIN_LENGTH       = 5;
    public const PASSWORD_MAX_LENGTH       = 60;

    public const EMAIL_MIN_LENGTH          = 6;
    public const EMAIL_MAX_LENGTH          = 40;

    public const AUTH_TOKEN_MIN_LENGTH     = 30;
    public const AUTH_TOKEN_MAX_LENGTH     = 30;

    public const VERIFIED_TOKEN_MIN_LENGTH = 30;
    public const VERIFIED_TOKEN_MAX_LENGTH = 30;

    public const TEMPLATE_MIN_LENGTH       = 2;
    public const TEMPLATE_MAX_LENGTH       = 10;

    public const IP_MIN_LENGTH             = 7;
    public const IP_MAX_LENGTH             = 39;

    public const REF_MIN_LENGTH            = 0;
    public const REF_MAX_LENGTH            = 30;

    public const USER_AGENT_MIN_LENGTH     = 0;
    public const USER_AGENT_MAX_LENGTH     = 150;

    public function getId(): string;
    public function getLogin(): string;
    public function getName(): string;
    public function getAvatar(): string;
    public function getPassword(): string;
    public function getEmail(): string;
    public function isRegComplete(): bool;
    public function isEmailVerified(): bool;
    public function emailVerified(): void;
    public function getAuthToken(): string;
    public function getVerifiedToken(): string;
    public function getTemplate(): string;
    public function setTemplate(string $template): void;
    public function getIp(): string;
    public function getRef(): string;
    public function getUserAgent(): string;
    public function isCanLike(): bool;
    public function getPostCount(): int;
    public function getCommentCount(): int;
    public function getMainCharacter(): MainCharacterInterface;
    public function getFloor(): FloorInterface;
    public function getStatus(): AccountStatus;
    public function getGroup(): AccountGroupInterface;
    public function getUpload(): AccountUpload;
    public function getCarma(): CarmaInterface;
    public function getCreatedAt(): DateTimeInterface;
    public function getUpdatedAt(): DateTimeInterface;
}
