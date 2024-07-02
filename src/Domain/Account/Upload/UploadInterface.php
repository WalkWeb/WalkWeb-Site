<?php

declare(strict_types=1);

namespace App\Domain\Account\Upload;

interface UploadInterface
{
    /**
     * Загруженный контент пользователем. Необходимо для расчета доступного места для загрузки медиа-файлов
     *
     * @return int
     */
    public function getUpload(): int;

    /**
     * Максимально доступный объем загруженного контента. Чем выше уровень основного персонажа - тем больше места
     * доступно
     *
     * @return int
     */
    public function getUploadMax(): int;

    /**
     * Оставшееся доступное место для загрузки медиа-файлов (в байтах)
     *
     * @return int
     */
    public function getUploadRemainder(): int;
}
