<?php

declare(strict_types=1);

namespace App\Domain\Post\Author;

use App\Domain\Account\Status\AccountStatusInterface;

/**
 * Для отображения информации об авторе поста создавать полноценный объект автора избыточно. По этому используется
 * отдельный объект, который имеет только те параметры, которые необходимы.
 *
 * @package App\Domain\Post\Author
 */
interface AuthorInterface
{
    /**
     * Возвращает ID автора поста
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Возвращает имя автора поста
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Возвращает url к аватару автора поста
     *
     * @return string
     */
    public function getAvatar(): string;

    /**
     * Возвращает уровень автора поста
     *
     * @return int
     */
    public function getLevel(): int;

    /**
     * Возвращает статус автора поста
     *
     * TODO Можно удалить
     *
     * @return AccountStatusInterface
     */
    public function getStatus(): AccountStatusInterface;
}
