<?php

declare(strict_types=1);

namespace App\Domain\Account;

use App\Domain\Account\Floor\FloorInterface;
use App\Domain\Account\Group\AccountGroupInterface;
use App\Domain\Account\Status\AccountStatus;
use App\Domain\Account\Upload\AccountUpload;
use DateTimeInterface;

interface AccountInterface
{
    // TODO min-max length value

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
    public function getFloor(): FloorInterface;
    public function getStatus(): AccountStatus;
    public function getGroup(): AccountGroupInterface;
    public function getUpload(): AccountUpload;
    public function getCreatedAt(): DateTimeInterface;
    public function getUpdatedAt(): DateTimeInterface;
}
