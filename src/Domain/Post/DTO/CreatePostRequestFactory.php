<?php

declare(strict_types=1);

namespace App\Domain\Post\DTO;

use App\Domain\Auth\AuthInterface;
use App\Domain\Post\PostException;
use App\Domain\Post\PostInterface;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class CreatePostRequestFactory
{
    use ValidationTrait;

    /**
     * TODO Добавить проверку, что указано несколько одинаковых новых тегов
     * TODO Убирать пробелы до и после названия тега, переводить в нижний регистр
     *
     * @param array $data
     * @param AuthInterface $user
     * @return CreatePostRequest
     * @throws AppException
     */
    public static function create(array $data, AuthInterface $user): CreatePostRequest
    {
        self::array($data, 'tags', PostException::INVALID_TAGS);

        $tags = [];

        foreach ($data['tags'] as $tag) {
            if (!is_string($tag)) {
                throw new AppException(PostException::INVALID_TAG);
            }

            $tags[] = $tag;
        }

        return new CreatePostRequest(
            self::validateTitle($data),
            self::validateContent($data),
            $tags,
            $user
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
}
