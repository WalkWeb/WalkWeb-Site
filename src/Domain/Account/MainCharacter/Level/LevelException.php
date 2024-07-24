<?php

declare(strict_types=1);

namespace App\Domain\Account\MainCharacter\Level;

use Exception;

class LevelException extends Exception
{
    public const INVALID_LEVEL                   = 'Invalid level';
    public const INVALID_ADD_EXP                 = 'Invalid add exp';
    public const INVALID_ACCOUNT_ID_DATA         = 'LevelException: Incorrect parameter "account_id", it required and type string';
    public const INVALID_ACCOUNT_ID_VALUE        = 'LevelException: Incorrect "account_id", should be min-max length: ';
    public const INVALID_MAIN_CHARACTER_ID_DATA  = 'LevelException: Incorrect parameter "main_character_id", it required and type string';
    public const INVALID_MAIN_CHARACTER_ID_VALUE = 'LevelException: Incorrect "main_character_id", should be min-max length: ';
    public const INVALID_CHARACTER_ID_DATA       = 'LevelException: Incorrect parameter "character_id", it required and type string';
    public const INVALID_CHARACTER_ID_VALUE      = 'LevelException: Incorrect "character_id", should be min-max length: ';
    public const INVALID_LEVEL_DATA              = 'LevelException: Incorrect parameter "character_level", it required and type integer';
    public const INVALID_LEVEL_VALUE             = 'LevelException: Incorrect "character_level", should be min-max value: ';
    public const INVALID_EXP_DATA                = 'LevelException: Incorrect parameter "character_exp", it required and type integer';
    public const INVALID_EXP_VALUE               = 'LevelException: Incorrect "character_exp", should be min-max value: ';
    public const INVALID_STAT_POINTS_DATA        = 'LevelException: Incorrect parameter "character_stat_points", it required and type integer';
    public const INVALID_STAT_POINTS_VALUE       = 'LevelException: Incorrect "character_stat_points", should be min-max value: ';
    public const INVALID_SKILL_POINTS_DATA        = 'LevelException: Incorrect parameter "character_skill_points", it required and type integer';
    public const INVALID_SKILL_POINTS_VALUE       = 'LevelException: Incorrect "character_skill_points", should be min-max value: ';
}
