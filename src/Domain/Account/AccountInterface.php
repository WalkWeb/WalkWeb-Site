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
    public const string AUTH_TOKEN                = 'auth';

    public const int LOGIN_MIN_LENGTH          = 3;
    public const int LOGIN_MAX_LENGTH          = 20;
    public const string LOGIN_PARENT           = '/^[a-zA-Z0-9а-яА-ЯёЁ\-_]*$/u';

    public const int NAME_MIN_LENGTH           = 3;
    public const int NAME_MAX_LENGTH           = 20;
    public const string NAME_PARENT            = '/^[a-zA-Z0-9а-яА-ЯёЁ\-_]*$/u';

    public const int AVATAR_MIN_LENGTH         = 5;
    public const int AVATAR_MAX_LENGTH         = 90;

    public const int PASSWORD_MIN_LENGTH       = 5;
    public const int PASSWORD_MAX_LENGTH       = 60;

    public const int EMAIL_MIN_LENGTH          = 6;
    public const int EMAIL_MAX_LENGTH          = 40;

    public const int AUTH_TOKEN_MIN_LENGTH     = 30;
    public const int AUTH_TOKEN_MAX_LENGTH     = 30;

    public const int VERIFIED_TOKEN_MIN_LENGTH = 30;
    public const int VERIFIED_TOKEN_MAX_LENGTH = 30;

    public const int TEMPLATE_MIN_LENGTH       = 2;
    public const int TEMPLATE_MAX_LENGTH       = 10;

    public const int IP_MIN_LENGTH             = 7;
    public const int IP_MAX_LENGTH             = 39;

    public const int REF_MIN_LENGTH            = 0;
    public const int REF_MAX_LENGTH            = 30;

    public const int USER_AGENT_MIN_LENGTH     = 0;
    public const int USER_AGENT_MAX_LENGTH     = 150;

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
