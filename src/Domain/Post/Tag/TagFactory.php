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
            self::string($data, 'name', TagException::INVALID_NAME),
            self::string($data, 'slug', TagException::INVALID_SLUG),
            self::string($data, 'icon', TagException::INVALID_ICON),
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
            $tag,
            strtolower(self::transliterate($tag)) . '-' . random_int(100, 999),
            '',
            null,
            false
        );
    }
}
