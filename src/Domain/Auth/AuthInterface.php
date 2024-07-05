<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\Account\Energy\EnergyInterface;
use App\Domain\Account\Group\AccountGroupInterface;
use App\Domain\Account\Notice\NoticeCollection;
use App\Domain\Account\Status\AccountStatusInterface;

/**
 * Auth - это особая модель (такой таблицы нет), которая создается при аутентификации пользователя. Хранит в себе
 * базовые параметры из разных таблиц, которые наиболее часто используются при взаимодействии с сайтом
 *
 * @package Portal\Auth
 */
interface AuthInterface
{
    /**
     * ID пользователя, если он успешно авторизован
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Имя пользователя
     *
     * @return string
     */
    public function getName(): string;

    /**
     * URL путь к аватару пользователя
     *
     * @return string
     */
    public function getAvatar(): string;

    /**
     * Группа пользователя
     *
     * @return AccountGroupInterface
     */
    public function getGroup(): AccountGroupInterface;

    /**
     * Статус пользователя
     *
     * @return AccountStatusInterface
     */
    public function getStatus(): AccountStatusInterface;

    /**
     * Возвращает энергию пользователя
     *
     * @return EnergyInterface
     */
    public function getEnergy(): EnergyInterface;

    /**
     * Может ли пользователь лайкать/дизлайкать
     *
     * @return bool
     */
    public function isCanLike(): bool;

    /**
     * Возвращает уведомления пользователя, которые необходимо отобразить
     *
     * @return NoticeCollection
     */
    public function getNotices(): NoticeCollection;

    /**
     * Часть функционала становится доступной только при достижении определенного уровня, соответственно в данных по
     * авторизации необходимо иметь уровень персонажа
     *
     * @return int
     */
    public function getLevel(): int;

    /**
     * @return int
     */
    public function getStatPoints(): int;

    /**
     * @param int $statPoints
     */
    public function setStatPoints(int $statPoints): void;
}
