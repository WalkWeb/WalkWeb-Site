<?php

declare(strict_types=1);

namespace App\Domain\Account\MainCharacter\Era;

class Era implements EraInterface
{
    private int $id;
    private string $name;
    private bool $actual;

    public function __construct(int $id, string $name, bool $actual)
    {
        $this->id = $id;
        $this->name = $name;
        $this->actual = $actual;
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
     * @return bool
     */
    public function isActual(): bool
    {
        return $this->actual;
    }
}
