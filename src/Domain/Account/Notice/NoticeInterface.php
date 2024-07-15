<?php

declare(strict_types=1);

namespace App\Domain\Account\Notice;

use DateTimeInterface;

/**
 * Уведомления предназначены для уведомления пользователю о каких-либо произошедших событиях. Например, опубликованный
 * им пост набрал больше количество лайков и перешел в новый статус
 *
 * @package Portal\Account\Notice
 */
interface NoticeInterface
{
    public const TYPE_INFO    = 1;
    public const TYPE_WARNING = 2;
    public const TYPE_SUCCESS = 3;

    // TODO Min-max message length: 5-600

    /**
     * Возвращает ID уведомления
     *
     * @return string
     */
    public function getId(): string;

    /**
     * TODO rename to getTypeId
     *
     * Возвращает тип уведомления
     *
     * @return int
     */
    public function getType(): int;

    // TODO getType: string

    /**
     * Возвращает ID аккаунта, к которому относится данное уведомление
     *
     * @return string
     */
    public function getAccountId(): string;

    /**
     * Возвращает сообщение уведомления
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Должно ли уведомление отображаться
     *
     * @return bool
     */
    public function isView(): bool;

    /**
     * Возвращает дату создания уведомления
     *
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface;
}
