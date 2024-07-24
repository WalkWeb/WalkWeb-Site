<?php

declare(strict_types=1);

namespace App\Domain\Theme;

interface ThemeInterface
{
    public const THEME_IT    = 1;
    public const THEME_GAME  = 2;
    public const THEME_VIDEO = 3;

    public function getId(): int;
    public function getName(): string;
}
