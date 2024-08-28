<?php

declare(strict_types=1);

namespace App\Domain\Community;

use Exception;

class CommunityException extends Exception
{
    public const ALREADY_EXIST               = 'CommunityCollection: community to be added already exists';
    public const EXPECTED_ARRAY              = 'CommunityCollectionFactory: expected array data';
    public const MEMBER_NOT_FOUND            = 'Member not found';
    public const NOT_FOUND                   = 'Community not found';

    public const INVALID_ID                  = 'Incorrect "id" parameter, it required and type string (uuid)';
    public const INVALID_LEVEL               = 'Incorrect parameter "level", it required and type integer';
    public const INVALID_NAME                = 'Incorrect "name" parameter, it required and type string';
    public const INVALID_SLUG                = 'Incorrect "slug" parameter, it required and type string';
    public const INVALID_DESCRIPTION         = 'Incorrect "description" parameter, it required and type string';
    public const INVALID_ICON                = 'Incorrect "icon" parameter, it required and type string';
    public const INVALID_ICON_SMALL          = 'Incorrect "icon_small" parameter, it required and type string';
    public const INVALID_HEAD_IMAGE          = 'Incorrect "head_image" parameter, it required and type string';
    public const INVALID_FOLLOWERS           = 'Incorrect parameter "followers", it required and type integer';
    public const INVALID_FIXED_POST_ID       = 'Incorrect "fixed_post_id" parameter, it required and type string or null';
    public const INVALID_MENU                = 'Incorrect "menu" parameter, it required and type string json or null';
    public const INVALID_OWNER_ID            = 'Incorrect "owner_id" parameter, it required and type string';
    public const INVALID_TOTAL_POST_COUNT    = 'Incorrect parameter "total_post_count", it required and type integer';
    public const INVALID_SILVER_POST_COUNT   = 'Incorrect parameter "silver_post_count", it required and type integer';
    public const INVALID_GOLD_POST_COUNT     = 'Incorrect parameter "gold_post_count", it required and type integer';
    public const INVALID_DIAMOND_POST_COUNT  = 'Incorrect parameter "diamond_post_count", it required and type integer';
    public const INVALID_TOTAL_COMMENT_COUNT = 'Incorrect parameter "total_comment_count", it required and type integer';
    public const INVALID_IS_JOINED           = 'Incorrect parameter "is_joined", it required and type integer or null';
    public const INVALID_CREATED_AT          = 'Incorrect "created_at" parameter, it required and type string date';
    public const INVALID_UPDATED_AT          = 'Incorrect "updated_at" parameter, it required and type string date';
}
