<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Genesis;

use App\Domain\Theme\ThemeInterface;

class Genesis implements GenesisInterface
{
    private int $id;
    private ThemeInterface $theme;
    private string $icon;
    private string $plural;
    private string $single;

    public function __construct(
        int $id,
        ThemeInterface $theme,
        string $icon,
        string $plural,
        string $single
    ) {
        $this->id = $id;
        $this->theme = $theme;
        $this->icon = $icon;
        $this->plural = $plural;
        $this->single = $single;
    }

    /**
     * @return string
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return ThemeInterface
     */
    public function getTheme(): ThemeInterface
    {
        return $this->theme;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getPlural(): string
    {
        return $this->plural;
    }

    /**
     * @return string
     */
    public function getSingle(): string
    {
        return $this->single;
    }
}
