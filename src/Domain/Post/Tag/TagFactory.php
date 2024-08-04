<?php

declare(strict_types=1);

namespace App\Domain\Post\Tag;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class TagFactory
{
    use ValidationTrait;

    /**
     * Создает тег на основе массива с данными
     *
     * @param array $data
     * @return TagInterface
     * @throws AppException
     */
    public function create(array $data): TagInterface
    {
        return new Tag(
            self::string($data, 'id', TagException::INVALID_ID),
            self::string($data, 'name', TagException::INVALID_NAME),
            self::string($data, 'slug', TagException::INVALID_SLUG),
            self::string($data, 'icon', TagException::INVALID_ICON),
            self::string($data, 'preview_post_id', TagException::INVALID_PREVIEW_POST_ID),
            self::bool($data, 'approved', TagException::INVALID_APPROVED)
        );
    }
}
