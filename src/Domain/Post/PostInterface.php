<?php

declare(strict_types=1);

namespace App\Domain\Post;

use DateTimeInterface;
use App\Domain\Pieces\Interfaces\ArrayableInterface;
use App\Domain\Post\Author\AuthorInterface;
use App\Domain\Post\Rating\RatingInterface;
use App\Domain\Post\Status\StatusInterface;
use App\Domain\Post\Tag\TagCollection;

/**
 * Важно: объект поста не содержит комментариев, чтобы не утяжелять объект для тех случаев, когда нам нужно сделать
 * коллекцию постов для отображения их списком
 *
 * На странице конкретного поста комментарии формируются отдельно, в самом контроллере
 *
 * @package App\Domain\Post
 */
interface PostInterface extends ArrayableInterface
{
    // TODO approved - одобрен ли пост для отображения (чтобы можно было включить предмодерацию публикации постов)
    // TODO moderated - проверен ли пост модератором
    // TODO html_content

    public const TITLE_MIN_LENGTH   = 2;
    public const TITLE_MAX_LENGTH   = 80;
    public const CONTENT_MIN_LENGTH = 2;
    public const CONTENT_MAX_LENGTH = 65534;

    /**
     * Возвращает ID поста
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Возвращает заголовок поста
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Устанавливает новый заголовок поста
     *
     * @param string $title
     * @throws PostException
     */
    public function setTitle(string $title): void;

    /**
     * Возвращает транслитерацию заголовка поста
     *
     * @return string
     */
    public function getSlug(): string;

    /**
     * Возвращает контент для отображения поста
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Устанавливает новый контент поста
     *
     * @param string $content
     * @throws PostException
     */
    public function setContent(string $content): void;

    /**
     * Возвращает статус поста: обычный, серебряный, золотой, брильянтовый
     *
     * По мере роста рейтинга поста он получает новые статусы, а его автор - опыт для аккаунта
     *
     * @return StatusInterface
     */
    public function getStatus(): StatusInterface;

    /**
     * Возвращает автора поста
     *
     * @return AuthorInterface
     */
    public function getAuthor(): AuthorInterface;

    /**
     * Возвращает параметры рейтинга поста
     *
     * @return RatingInterface
     */
    public function getRating(): RatingInterface;

    /**
     * Возвращает количество комментариев поста
     *
     * @return int
     */
    public function getCommentsCount(): int;

    /**
     * Опубликован ли пост
     *
     * @return bool
     */
    public function isPublished(): bool;

    /**
     * Возвращает коллекцию тегов поста
     *
     * @return TagCollection
     */
    public function getTags(): TagCollection;

    /**
     * Устанавливает новые теги поста
     *
     * @param TagCollection $tags
     */
    public function setTags(TagCollection $tags): void;

    /**
     * Лайкал/дизлайкал ли авторизованный пользователь данный пост. Если пользователь не авторизован - вернет false
     *
     * @return bool
     */
    public function isLiked(): bool;

    /**
     * Возвращает дату создания поста
     *
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface;

    /**
     * Возвращает дату последнего редактирования поста
     *
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface;
}
