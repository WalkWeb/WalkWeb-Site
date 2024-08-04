<?php

declare(strict_types=1);

namespace App\Domain\Post\Tag;

use App\Domain\Pieces\Interfaces\ArrayableInterface;

interface TagInterface extends ArrayableInterface
{
    /**
     * Возвращает id тега
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Возвращает название тега
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Возвращает транслитерацию названия тега
     *
     * @return string
     */
    public function getSlug(): string;

    /**
     * Возвращает иконку тега
     *
     * @return string
     */
    public function getIcon(): string;

    /**
     * Возвращает id поста, который отмечен как информация этого тега - любое информационное содержимое, которое будет
     * отображаться в самом начале
     *
     * Если такого поста нет - будет возвращена пустая строка
     *
     * @return string
     */
    public function getPreviewPostId(): string;

    /**
     * Является ли тег одобренным. Одобренные теги подставляются как подсказки (когда пользователь указывает тег).
     *
     * Сделано для того, чтобы теги сделанные с ошибками не выводились в подсказках
     *
     * @return bool
     */
    public function isApproved(): bool;
}
