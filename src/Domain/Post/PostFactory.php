<?php

declare(strict_types=1);

namespace App\Domain\Post;

use App\Domain\Post\Author\AuthorFactory;
use App\Domain\Post\Rating\RatingFactory;
use App\Domain\Post\Status\Status;
use App\Domain\Post\Tag\TagCollection;
use App\Domain\Post\Tag\TagFactory;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class PostFactory
{
    use ValidationTrait;

    /**
     * Создает объект поста на основе массива с данными
     *
     * @param array $data
     * @return PostInterface
     * @throws AppException
     */
    public static function create(array $data): PostInterface
    {
        $title = self::string($data, 'title', PostException::INVALID_TITLE);
        $content = self::string($data, 'content', PostException::INVALID_CONTENT);
        $statusId = self::int($data, 'status_id', PostException::INVALID_STATUS_ID);

        self::stringMinMaxLength(
            $title,
            PostInterface::TITLE_MIN_LENGTH,
            PostInterface::TITLE_MAX_LENGTH,
            PostException::INVALID_TITLE_VALUE . PostInterface::TITLE_MIN_LENGTH . '-' . PostInterface::TITLE_MAX_LENGTH
        );

        self::stringMinMaxLength(
            $content,
            PostInterface::CONTENT_MIN_LENGTH,
            PostInterface::CONTENT_MAX_LENGTH,
            PostException::INVALID_CONTENT_VALUE . PostInterface::CONTENT_MIN_LENGTH . '-' . PostInterface::CONTENT_MAX_LENGTH
        );

        $tagCollection = new TagCollection();

        foreach (self::array($data, 'tags', PostException::INVALID_TAGS) as $tagData) {
            if (!is_array($tagData)) {
                throw new AppException(PostException::INVALID_TAGS_DATA);
            }

            $tagCollection->add(TagFactory::create($tagData));
        }

        return new Post(
            self::uuid($data, 'id', PostException::INVALID_ID),
            $title,
            self::string($data, 'slug', PostException::INVALID_SLUG),
            $content,
            new Status($statusId),
            AuthorFactory::create($data),
            RatingFactory::create($data),
            self::int($data, 'comments_count', PostException::INVALID_COMMENTS_COUNT),
            (bool)self::int($data, 'published', PostException::INVALID_PUBLISHED),
            $tagCollection,
            self::bool($data, 'is_liked', PostException::INVALID_IS_LIKED_DATA),
            self::date($data, 'created_at', PostException::INVALID_CREATED_AT),
            self::dateOrNull($data, 'updated_at', PostException::INVALID_UPDATED_AT),
        );
    }
}
