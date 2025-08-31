<?php

declare(strict_types=1);

namespace App\Domain\Theme;

interface ThemeInterface
{
    public const int THEME_IT    = 1;
    public const int THEME_GAME  = 2;
    public const int THEME_VIDEO = 3;

    public function getId(): int;
    public function getName(): string;
}
