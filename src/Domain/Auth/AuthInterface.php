<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\Account\Energy\EnergyInterface;
use App\Domain\Account\Group\AccountGroupInterface;
use App\Domain\Account\MainCharacter\Level\LevelInterface;
use App\Domain\Account\Notice\NoticeCollection;
use App\Domain\Account\Status\AccountStatusInterface;
use App\Domain\Account\Upload\UploadInterface;

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
     * Возвращает токен верификации действия
     *
     * @return string
     */
    public function getVerifiedToken(): string;

    /**
     * Возвращает id актуального основного персонажа
     *
     * @return string
     */
    public function getMainCharacterId(): string;

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
     * @return LevelInterface
     */
    public function getLevel(): LevelInterface;

    /**
     * Возвращает используемый пользователем шаблон сайта
     *
     * @return string
     */
    public function getTemplate(): string;

    /**
     * Подтвержден ли email
     *
     * @return bool
     */
    public function isEmailVerified(): bool;

    /**
     * Указывает, что email подтвержден
     */
    public function emailVerified(): void;

    /**
     * Данные по занятому и оставшемуся месту на диске
     *
     * @return UploadInterface
     */
    public function getUpload(): UploadInterface;
}
