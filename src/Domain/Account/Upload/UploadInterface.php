<?php

declare(strict_types=1);

namespace App\Domain\Account\Upload;

interface UploadInterface
{
    // TODO min-max value

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
