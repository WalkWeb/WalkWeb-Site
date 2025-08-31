<?php

declare(strict_types=1);

namespace App\Domain\Account\DTO;

class LoginRequest
{
    public const int REDIRECT_MAX_LENGTH = 100;

    private string $login;
    private string $password;
    private string $redirectUrl;

    public function __construct(string $login, string $password, string $redirectUrl)
    {
        $this->login = $login;
        $this->password = $password;
        $this->redirectUrl = $redirectUrl;
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
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }
}
