<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Level;

use WalkWeb\NW\AppException;

interface LevelInterface
{
    public const ACCOUNT_ID_MIN_LENGTH   = 5;
    public const ACCOUNT_ID_MAX_LENGTH   = 36;

    public const CHARACTER_ID_MIN_LENGTH = 5;
    public const CHARACTER_ID_MAX_LENGTH = 36;

    public const MIN_LEVEL               = 1;
    public const MAX_LEVEL               = 100;

    public const MIN_EXP                 = 0;
    public const MAX_EXP                 = 9110489; // 8859190 exp_total + 251300 exp_to_lvl - 1

    public const MIN_STAT_POINTS         = 0;
    public const MAX_STAT_POINTS         = 495; // 99 уровней * 5 очков на уровень

    public const MIN_SKILL_POINTS        = 0;
    public const MAX_SKILL_POINTS        = 99;

    public const ADD_STAT_POINT          = 5; // количество добавляемых очков характеристик на каждый новый уровень
    public const ADD_SKILL_POINT         = 1; // количество добавляемых очков навыков на каждый новый уровень

    /**
     * @return string
     */
    public function getAccountId(): string;

    /**
     * @return string
     */
    public function getMainCharacterId(): string;

    /**
     * @return string
     */
    public function getCharacterId(): string;

    /**
     * Возвращает значение уровня
     *
     * @return int
     */
    public function getLevel(): int;

    /**
     * Возвращает суммарное количество опыта (персонажа/аккаунта)
     *
     * @return int
     */
    public function getExp(): int;

    /**
     * Возвращает количество опыта, необходимого для получения следующего уровня. Используется для построения полоски
     * прогресса прокачки
     *
     * @return int
     */
    public function getExpToLevel(): int;

    /**
     * Возвращает количество опыта, набранного на текущем уровне. Используется для построения полоски прогресса прокачки
     *
     * @return int
     */
    public function getExpAtLevel(): int;

    /**
     * Возвращает % набранного опыта от необходимого количества до следующего уровня. Используется для построения
     * полоски прогресса прокачки
     *
     * @return int
     */
    public function getExpBarWeight(): int;

    /**
     * Добавляет опыт, если опыта достаточно для получения следующего уровня (уровней) - повышает уровни и начисляет
     * stat points
     *
     * @param int $addExp
     * @throws AppException
     */
    public function addExp(int $addExp): void;

    /**
     * Возвращает количество очков характеристик
     *
     * @return int
     */
    public function getStatPoints(): int;

    /**
     * Возвращает количество очков навыков
     *
     * @return int
     */
    public function getSkillPoints(): int;
}
