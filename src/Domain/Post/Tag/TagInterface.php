<?php

declare(strict_types=1);

namespace App\Domain\Post\Tag;

use App\Domain\Pieces\Interfaces\ArrayableInterface;

interface TagInterface extends ArrayableInterface
{
    // TODO parent_id - для уже существующих тегов написанных с ошибками отображать в подсказках корректный родительский тег
    // TODO created_at

    // TODO min-max length name value

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
     * @return string|null
     */
    public function getPreviewPostId(): ?string;

    /**
     * Является ли тег одобренным. Одобренные теги подставляются как подсказки (когда пользователь указывает тег).
     *
     * Сделано для того, чтобы теги сделанные с ошибками не выводились в подсказках
     *
     * @return bool
     */
    public function isApproved(): bool;
}
