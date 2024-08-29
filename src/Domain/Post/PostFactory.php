<?php

declare(strict_types=1);

namespace App\Domain\Post;

use App\Domain\Post\Author\Author;
use App\Domain\Post\Author\AuthorFactory;
use App\Domain\Post\DTO\CreatePostRequest;
use App\Domain\Post\Rating\Rating;
use App\Domain\Post\Rating\RatingFactory;
use App\Domain\Post\Status\PostStatus;
use App\Domain\Post\Tag\TagCollection;
use DateTime;
use Exception;
use Ramsey\Uuid\Uuid;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\StringTrait;
use WalkWeb\NW\Traits\ValidationTrait;

class PostFactory
{
    use ValidationTrait;
    use StringTrait;

    /**
     * Создает объект поста на основе массива с данными
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
            self::validateSlug($data),
            self::validateContent($data),
            self::validateHtmlContent($data),
            new PostStatus(self::int($data, 'status_id', PostException::INVALID_STATUS_ID)),
            AuthorFactory::create($data),
            RatingFactory::create($data),
            self::int($data, 'comments_count', PostException::INVALID_COMMENTS_COUNT),
            (bool)self::int($data, 'published', PostException::INVALID_PUBLISHED),
            $tags,
            self::bool($data, 'is_liked', PostException::INVALID_IS_LIKED),
            self::stringOrNull($data, 'community_slug', PostException::INVALID_COMMUNITY_SLUG) ?? '',
            self::stringOrNull($data, 'community_name', PostException::INVALID_COMMUNITY_NAME) ?? '',
            self::date($data, 'created_at', PostException::INVALID_CREATED_AT),
            self::dateOrNull($data, 'updated_at', PostException::INVALID_UPDATED_AT),
        );
    }

    /**
     * @param CreatePostRequest $request
     * @param TagCollection $tags
     * @param string $communitySlug
     * @return PostInterface
     * @throws Exception
     */
    public static function createNew(CreatePostRequest $request, TagCollection $tags, string $communitySlug): PostInterface
    {
        $slug = strtolower(self::transliterate($request->getTitle())) . '-' . random_int(10000, 99999);

        return new Post(
            Uuid::uuid4()->toString(),
            $request->getTitle(),
            $slug,
            $request->getContent(),
            self::convertToHtml($request->getContent()),
            new PostStatus(PostStatus::DEFAULT),
            new Author(
                $request->getAuthor()->getId(),
                $request->getAuthor()->getName(),
                $request->getAuthor()->getAvatar(),
                $request->getAuthor()->getLevel()->getLevel(),
                $request->getAuthor()->getStatus()
            ),
            new Rating(0, 0, 0),
            0,
            PostInterface::DEFAULT_PUBLISHED,
            $tags,
            false,
            $communitySlug,
            '',
            new DateTime(),
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

    /**
     * @param string $content
     * @return string
     */
    private static function convertToHtml(string $content): string
    {
        $text = htmlspecialchars($content);

        $text = preg_replace( '[\[img\]([^"\s]+?)\[/img\]]m', PostInterface::IMAGE_TEMPLATE, $text);

        return str_replace(
            ['[p]', '[/p]', '[video]', '[/video]', '[line]', '[h2]', '[/h2]', '[br]'],
            ['<p>', '</p>', PostInterface::VIDEO_PREFIX, PostInterface::VIDEO_SUFFIX, PostInterface::LINE, '<h2>', '</h2>', '<br />'],
            $text
        );
    }
}
