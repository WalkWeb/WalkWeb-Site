<?php

declare(strict_types=1);

namespace App\Domain\Post\Author;

use App\Domain\Account\Status\AccountStatusInterface;

class Author implements AuthorInterface
{
    private string $id;
    private string $name;
    private string $avatar;
    private int $level;
    private AccountStatusInterface $status;

    public function __construct(string $id, string $name, string $avatar, int $level, AccountStatusInterface $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->avatar = $avatar;
        $this->level = $level;
        $this->status = $status;
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
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return AccountStatusInterface
     */
    public function getStatus(): AccountStatusInterface
    {
        return $this->status;
    }
}
