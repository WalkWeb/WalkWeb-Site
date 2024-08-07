<?php

declare(strict_types=1);

namespace App\Domain\Post;

use App\Domain\Post\Author\AuthorFactory;
use App\Domain\Post\Rating\RatingFactory;
use App\Domain\Post\Status\PostStatus;
use App\Domain\Post\Tag\TagCollection;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class PostFactory
{
    use ValidationTrait;

    /**
     * Создает объект поста на основе массива с данными
     *
     * TODO Add mix-max length slug validate
     *
     * @param array $data
     * @param TagCollection $tags
     * @return PostInterface
     * @throws AppException
     */
    public static function create(array $data, TagCollection $tags): PostInterface
    {
        return new Post(
            self::uuid($data, 'id', PostException::INVALID_ID),
            self::validateTitle($data),
            self::string($data, 'slug', PostException::INVALID_SLUG),
            self::validateContent($data),
            self::validateHtmlContent($data),
            new PostStatus(self::int($data, 'status_id', PostException::INVALID_STATUS_ID)),
            AuthorFactory::create($data),
            RatingFactory::create($data),
            self::int($data, 'comments_count', PostException::INVALID_COMMENTS_COUNT),
            (bool)self::int($data, 'published', PostException::INVALID_PUBLISHED),
            $tags,
            self::bool($data, 'is_liked', PostException::INVALID_IS_LIKED_DATA),
            self::date($data, 'created_at', PostException::INVALID_CREATED_AT),
            self::dateOrNull($data, 'updated_at', PostException::INVALID_UPDATED_AT),
        );
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function validateTitle(array $data): string
    {
        $title = self::string($data, 'title', PostException::INVALID_TITLE);

        self::stringMinMaxLength(
            $title,
            PostInterface::TITLE_MIN_LENGTH,
            PostInterface::TITLE_MAX_LENGTH,
            PostException::INVALID_TITLE_VALUE . PostInterface::TITLE_MIN_LENGTH . '-' . PostInterface::TITLE_MAX_LENGTH
        );

        return $title;
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function validateContent(array $data): string
    {
        $content = self::string($data, 'content', PostException::INVALID_CONTENT);

        self::stringMinMaxLength(
            $content,
            PostInterface::CONTENT_MIN_LENGTH,
            PostInterface::CONTENT_MAX_LENGTH,
            PostException::INVALID_CONTENT_LENGTH . PostInterface::CONTENT_MIN_LENGTH . '-' . PostInterface::CONTENT_MAX_LENGTH
        );

        return $content;
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function validateHtmlContent(array $data): string
    {
        $content = self::string($data, 'html_content', PostException::INVALID_HTML_CONTENT);

        self::stringMinMaxLength(
            $content,
            PostInterface::HTML_CONTENT_MIN_LENGTH,
            PostInterface::HTML_CONTENT_MAX_LENGTH,
            PostException::INVALID_HTML_CONTENT_LENGTH . PostInterface::HTML_CONTENT_MIN_LENGTH . '-' . PostInterface::HTML_CONTENT_MAX_LENGTH
        );

        return $content;
    }
}
