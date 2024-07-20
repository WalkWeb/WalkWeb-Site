<?php

declare(strict_types=1);

namespace App\Domain\Account;

use App\Domain\Account\Floor\FloorInterface;
use App\Domain\Account\Group\AccountGroupInterface;
use App\Domain\Account\MainCharacter\MainCharacterInterface;
use App\Domain\Account\Status\AccountStatus;
use App\Domain\Account\Upload\AccountUpload;
use DateTimeInterface;

interface AccountInterface
{
    // TODO min-max avatar length

    public const AUTH_TOKEN                = 'auth';

    public const LOGIN_MIN_LENGTH          = 4;
    public const LOGIN_MAX_LENGTH          = 20;
    public const LOGIN_PARENT              = '/^[a-zA-Z0-9а-яА-ЯёЁ\-_]*$/u';

    public const NAME_MIN_LENGTH           = 4;
    public const NAME_MAX_LENGTH           = 20;
    public const NAME_PARENT               = '/^[a-zA-Z0-9а-яА-ЯёЁ\-_]*$/u';

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
    public const USER_AGENT_MAX_LENGTH     = 100;

    // TODO move to UploadInterface
    public const UPLOAD_MIN_VALUE          = 0;
    public const UPLOAD_MAX_VALUE          = 300*1024*1024;
    public const UPLOAD_MAX_BASE           = 20*1024*1024;
    public const UPLOAD_PER_LEVEL          = 3*1024*1024;
    public const UPLOAD_PER_STAT           = 1024*1024;
    // TODO MAX_UPLOAD_MIN_VALUE >= 1
    // TODO MAX_UPLOAD_MAX_VALUE >= 300*1024*1024

    public function getId(): string;
    public function getLogin(): string;
    public function getName(): string;
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
    public function getMainCharacter(): MainCharacterInterface;
    public function getFloor(): FloorInterface;
    public function getStatus(): AccountStatus;
    public function getGroup(): AccountGroupInterface;
    public function getUpload(): AccountUpload;
    public function getCreatedAt(): DateTimeInterface;
    public function getUpdatedAt(): DateTimeInterface;
}
