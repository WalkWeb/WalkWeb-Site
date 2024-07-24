<?php

declare(strict_types=1);

namespace App\Domain\Theme;

use App\Domain\Account\AccountException;
use WalkWeb\NW\AppException;

class Theme implements ThemeInterface
{
    private static array $map = [
        self::THEME_IT    => 'it',
        self::THEME_GAME  => 'game',
        self::THEME_VIDEO => 'video',
    ];

    private int $id;

    private string $name;

    /**
     * @param int $id
     * @throws AppException
     */
    public function __construct(int $id)
    {
        $this->id = $id;
        $this->setName($id);
    }

    /**
     * @return int
     */
    public function getId(): int
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
     * @param int $id
     * @throws AppException
     */
    private function setName(int $id): void
    {
        if (!array_key_exists($id, self::$map)) {
            throw new AppException(AccountException::UNKNOWN_THEME_ID . ': ' . $id);
        }

        $this->name = self::$map[$id];
    }
}
