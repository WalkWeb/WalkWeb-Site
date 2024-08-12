<?php

declare(strict_types=1);

namespace App\Domain\Post\Collection;

use App\Domain\Post\PostException;
use App\Domain\Post\PostInterface;
use App\Domain\Post\Rating\RatingFactory;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class PostListFactory
{
    use ValidationTrait;

    /**
     * @param array $data
     * @return PostListInterface
     * @throws AppException
     */
    public static function create(array $data): PostListInterface
    {
        $tags = self::array($data, 'tags', PostException::INVALID_TINY_TAGS);

        foreach ($tags as $tag) {
            if (!is_array($tag)) {
                throw new AppException(PostException::INVALID_TAG_DATA);
            }

            if (!array_key_exists('slug', $tag) || !is_string($tag['slug'])) {
                throw new AppException(PostException::INVALID_TAG_SLUG);
            }

            if (!array_key_exists('name', $tag) || !is_string($tag['name'])) {
                throw new AppException(PostException::INVALID_TAG_NAME);
            }
        }

        return new PostList(
            self::uuid($data, 'id', PostException::INVALID_ID),
            self::validateTitle($data),
            self::validateSlug($data),
            self::validateHtmlContent($data),
            self::string($data, 'author_name', PostException::INVALID_AUTHOR_NAME),
            RatingFactory::create($data),
            self::int($data, 'comments_count', PostException::INVALID_COMMENTS_COUNT),
            $tags,
            self::bool($data, 'is_liked', PostException::INVALID_IS_LIKED),
            self::date($data, 'created_at', PostException::INVALID_CREATED_AT),
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
            PostException::INVALID_TITLE_LENGTH . PostInterface::TITLE_MIN_LENGTH . '-' . PostInterface::TITLE_MAX_LENGTH
        );

        return $title;
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function validateSlug(array $data): string
    {
        $slug = self::string($data, 'slug', PostException::INVALID_SLUG);

        self::stringMinMaxLength(
            $slug,
            PostInterface::SLUG_MIN_LENGTH,
            PostInterface::SLUG_MAX_LENGTH,
            PostException::INVALID_SLUG_LENGTH . PostInterface::SLUG_MIN_LENGTH . '-' . PostInterface::SLUG_MAX_LENGTH
        );

        return $slug;
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
