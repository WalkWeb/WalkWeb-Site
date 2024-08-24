<?php

declare(strict_types=1);

namespace App\Domain\Post\Tag;

use Exception;
use Ramsey\Uuid\Uuid;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\StringTrait;
use WalkWeb\NW\Traits\ValidationTrait;

class TagFactory
{
    use ValidationTrait;
    use StringTrait;

    /**
     * Создает тег на основе массива с данными
     *
     * @param array $data
     * @return TagInterface
     * @throws AppException
     */
    public static function create(array $data): TagInterface
    {
        return new Tag(
            self::string($data, 'id', TagException::INVALID_ID),
            self::validateName($data),
            self::validateSlug($data),
            self::validateIcon($data),
            self::stringOrNull($data, 'preview_post_id', TagException::INVALID_PREVIEW_POST_ID),
            (bool)self::int($data, 'approved', TagException::INVALID_APPROVED)
        );
    }

    /**
     * @param string $tag
     * @return TagInterface
     * @throws Exception
     */
    public static function createNew(string $tag): TagInterface
    {
        return new Tag(
            Uuid::uuid4()->toString(),
            mb_strtolower($tag),
            mb_strtolower(self::transliterate($tag)) . '-' . random_int(100, 999), // TODO Подумать над убиранием префикса
            '',
            null,
            false
        );
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function validateName(array $data): string
    {
        $name = self::string($data, 'name', TagException::INVALID_NAME);

        self::stringMinMaxLength(
            $name,
            TagInterface::NAME_MIN_LENGTH,
            TagInterface::NAME_MAX_LENGTH,
            TagException::INVALID_NAME_LENGTH . TagInterface::NAME_MIN_LENGTH . '-' . TagInterface::NAME_MAX_LENGTH
        );

        return $name;
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function validateSlug(array $data): string
    {
        $slug = self::string($data, 'slug', TagException::INVALID_SLUG);

        self::stringMinMaxLength(
            $slug,
            TagInterface::SLUG_MIN_LENGTH,
            TagInterface::SLUG_MAX_LENGTH,
            TagException::INVALID_SLUG_LENGTH . TagInterface::SLUG_MIN_LENGTH . '-' . TagInterface::SLUG_MAX_LENGTH
        );

        return $slug;
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function validateIcon(array $data): string
    {
        $icon = self::string($data, 'icon', TagException::INVALID_ICON);

        self::stringMinMaxLength(
            $icon,
            TagInterface::ICON_MIN_LENGTH,
            TagInterface::ICON_MAX_LENGTH,
            TagException::INVALID_ICON_LENGTH . TagInterface::ICON_MIN_LENGTH . '-' . TagInterface::ICON_MAX_LENGTH
        );

        return $icon;
    }
}
