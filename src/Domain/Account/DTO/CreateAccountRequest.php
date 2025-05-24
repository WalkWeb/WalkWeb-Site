<?php

declare(strict_types=1);

namespace App\Domain\Account\DTO;

class CreateAccountRequest
{
    private string $login;
    private string $email;
    private string $password;
    private int $floor;
    private int $genesis;
    private int $profession;
    private int $avatar;
    private string $referral;
    private string $userAgent;
    private string $ip;

    public function __construct(
        string $login,
        string $email,
        string $password,
        int $floor,
        int $genesis,
        int $profession,
        int $avatar,
        string $referral,
        string $userAgent,
        string $ip
    ) {
        $this->login = $login;
        $this->email = $email;
        $this->password = $password;
        $this->floor = $floor;
        $this->genesis = $genesis;
        $this->profession = $profession;
        $this->avatar = $avatar;
        $this->referral = $referral;
        $this->userAgent = $userAgent;
        $this->ip = $ip;
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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getFloor(): int
    {
        return $this->floor;
    }

    /**
     * @return int
     */
    public function getGenesis(): int
    {
        return $this->genesis;
    }

    /**
     * @return int
     */
    public function getProfession(): int
    {
        return $this->profession;
    }

    /**
     * @return int
     */
    public function getAvatar(): int
    {
        return $this->avatar;
    }

    /**
     * @return string
     */
    public function getReferral(): string
    {
        return $this->referral;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }
}
