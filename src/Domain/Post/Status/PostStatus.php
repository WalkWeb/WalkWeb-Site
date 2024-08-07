<?php

declare(strict_types=1);

namespace App\Domain\Post\Status;

use WalkWeb\NW\AppException;

class PostStatus implements PostStatusInterface
{
    private static array $map = [
        self::DEFAULT => 'Default',
        self::SILVER  => 'Silver',
        self::GOLD    => 'Gold',
        self::DIAMOND => 'Diamond',
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
            throw new AppException(StatusException::UNKNOWN_POST_STATUS_ID . ': ' . $id);
        }

        $this->name = self::$map[$id];
    }
}
