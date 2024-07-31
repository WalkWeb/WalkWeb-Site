<?php

declare(strict_types=1);

namespace App\Domain\Account\Notice;

// TODO Delete

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
    public const ACTUAL_LIMIT = 8;

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
     * @param string $accountId
     * @param int $offset
     * @param int $limit
     * @return NoticeCollection
     */
    public function getAll(string $accountId, int $offset, int $limit): NoticeCollection;

    /**
     * Возвращает все актуальные уведомления для указанного пользователя
     *
     * @param string $accountId
     * @param int $total
     * @return NoticeCollection
     */
    public function getActual(string $accountId, int $total = self::ACTUAL_LIMIT): NoticeCollection;
}
