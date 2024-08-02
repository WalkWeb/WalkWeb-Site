<?php

declare(strict_types=1);

namespace App\Domain\Account\Upload;

interface UploadInterface
{
    // TODO min-max value
    // TODO MAX_UPLOAD_MIN_VALUE >= 1
    // TODO MAX_UPLOAD_MAX_VALUE >= 300*1024*1024

    public const UPLOAD_MIN_VALUE          = 0;
    public const UPLOAD_MAX_VALUE          = 300*1024*1024;
    public const UPLOAD_MAX_BASE           = 20*1024*1024;
    public const UPLOAD_PER_LEVEL          = 3*1024*1024;
    public const UPLOAD_PER_STAT           = 1024*1024;

    /**
     * Загруженный контент пользователем. Необходимо для расчета доступного места для загрузки медиа-файлов
     *
     * @return int
     */
    public function getUpload(): int;

    /**
     * @return float
     */
    public function getUploadMb(): float;

    /**
     * Максимально доступный объем загруженного контента. Чем выше уровень основного персонажа - тем больше места
     * доступно
     *
     * @return int
     */
    public function getUploadMax(): int;

    /**
     * @return float
     */
    public function getUploadMaxMb(): float;

    /**
     * Оставшееся доступное место для загрузки медиа-файлов (в байтах)
     *
     * @return int
     */
    public function getUploadRemainder(): int;

    /**
     * Возвращает % заполненности места на диске
     *
     * @return int
     */
    public function getUploadBarWeight(): int;
}
