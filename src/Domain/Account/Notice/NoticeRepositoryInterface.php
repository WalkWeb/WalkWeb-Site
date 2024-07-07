<?php

declare(strict_types=1);

namespace App\Domain\Account\Notice;

use WalkWeb\NW\AppException;

/**
 * Доменная модель ничего не знает и не должна знать о хранилище данных. Здесь представлен лишь требуемый интерфейс для
 * работы. Конкретная реализация должна быть сделана непосредственном в самом проекте, который уже будет знать о базе, в
 * которой будут храниться данные
 *
 * @package Portal\Account\Energy
 */
interface NoticeRepositoryInterface
{
    /**
     * Получает данные из базы и создает объект уведомления пользователя
     *
     * @param string $id
     * @return NoticeInterface
     * @throws AppException
     * @throws NoticeException
     */
    public function get(string $id): NoticeInterface;

    /**
     * Сохраняет обновленные данные по уведомлению в базе
     *
     * @param NoticeInterface $notice
     */
    public function add(NoticeInterface $notice): void;

    /**
     * Возвращает все актуальные уведомления для указанного пользователя
     *
     * @param string $accountId
     * @return NoticeCollection
     */
    public function getActual(string $accountId): NoticeCollection;
}
