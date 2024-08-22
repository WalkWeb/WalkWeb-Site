<?php

declare(strict_types=1);

namespace App\Domain\Rating\DTO\Genesis;

use Exception;

class GenesisRatingException extends Exception
{
    public const ALREADY_EXIST         = 'GenesisRatingCollection: account to be added already exists';
    public const EXPECTED_ARRAY        = 'GenesisRatingCollectionFactory: expected array data';

    public const INVALID_ID            = 'Incorrect "id" parameter, it required and type int';
    public const INVALID_ICON          = 'Incorrect "icon" parameter, it required and type string';
    public const INVALID_NAME          = 'Incorrect "name" parameter, it required and type string';
    public const INVALID_MEMBER_COUNT  = 'Incorrect "member_count" parameter, it required and type int or null';
    public const INVALID_POST_COUNT    = 'Incorrect "post_count" parameter, it required and type int or null';
    public const INVALID_COMMENT_COUNT = 'Incorrect "comment_count" parameter, it required and type int or null';
    public const INVALID_CARMA_COUNT   = 'Incorrect "carma_count" parameter, it required and type int or null';
}
