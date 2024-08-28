<?php

declare(strict_types=1);

namespace App\Domain\Community;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class CommunityFactory
{
    use ValidationTrait;

    /**
     * TODO min-max value
     *
     * @param array $data
     * @return CommunityInterface
     * @throws AppException
     */
    public static function create(array $data): CommunityInterface
    {
        return new Community(
            self::uuid($data, 'id', CommunityException::INVALID_ID),
            self::int($data, 'level', CommunityException::INVALID_LEVEL),
            self::string($data, 'name', CommunityException::INVALID_NAME),
            self::string($data, 'slug', CommunityException::INVALID_SLUG),
            self::string($data, 'description', CommunityException::INVALID_DESCRIPTION),
            self::string($data, 'icon', CommunityException::INVALID_ICON),
            self::string($data, 'icon_small', CommunityException::INVALID_ICON_SMALL),
            self::string($data, 'head_image', CommunityException::INVALID_HEAD_IMAGE),
            self::int($data, 'followers', CommunityException::INVALID_FOLLOWERS),
            self::uuidOrNull($data, 'fixed_post_id', CommunityException::INVALID_FIXED_POST_ID),
            self::stringOrNull($data, 'menu', CommunityException::INVALID_MENU),
            self::uuid($data, 'owner_id', CommunityException::INVALID_OWNER_ID),
            self::int($data, 'total_post_count', CommunityException::INVALID_TOTAL_POST_COUNT),
            self::int($data, 'silver_post_count', CommunityException::INVALID_SILVER_POST_COUNT),
            self::int($data, 'gold_post_count', CommunityException::INVALID_GOLD_POST_COUNT),
            self::int($data, 'diamond_post_count', CommunityException::INVALID_DIAMOND_POST_COUNT),
            self::int($data, 'total_comment_count', CommunityException::INVALID_TOTAL_COMMENT_COUNT),
            (bool)self::intOrNull($data, 'is_joined', CommunityException::INVALID_IS_JOINED),
            self::date($data, 'created_at', CommunityException::INVALID_CREATED_AT),
            self::date($data, 'updated_at', CommunityException::INVALID_UPDATED_AT),
        );
    }
}
