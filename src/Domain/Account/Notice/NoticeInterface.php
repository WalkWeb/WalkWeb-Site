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
    public const REGISTER_START = 'Начало регистрации на портале';
    public const EMAIL_APPROVE  = 'Email успешно подтвержден';

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
     * Возвращает id типа уведомления
     *
     * @return int
     */
    public function getTypeId(): int;

    /**
     * Возвращает текстовое название типа уведомления
     *
     * @return string
     */
    public function getType(): string;

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

    /**
     * Возвращает как давно было создано уведомление
     *
     * @return string
     */
    public function getElapsedCreatedAt(): string;
}
