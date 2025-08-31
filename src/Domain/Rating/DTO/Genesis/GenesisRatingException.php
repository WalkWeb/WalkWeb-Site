<?php

declare(strict_types=1);

namespace App\Domain\Rating\DTO\Genesis;

use Exception;

class GenesisRatingException extends Exception
{
    public const string ALREADY_EXIST         = 'GenesisRatingCollection: account to be added already exists';
    public const string EXPECTED_ARRAY        = 'GenesisRatingCollectionFactory: expected array data';

    public const string INVALID_ID            = 'Incorrect "id" parameter, it required and type int';
    public const string INVALID_ICON          = 'Incorrect "icon" parameter, it required and type string';
    public const string INVALID_NAME          = 'Incorrect "name" parameter, it required and type string';
    public const string INVALID_MEMBER_COUNT  = 'Incorrect "member_count" parameter, it required and type int or null';
    public const string INVALID_POST_COUNT    = 'Incorrect "post_count" parameter, it required and type string or null';
    public const string INVALID_COMMENT_COUNT = 'Incorrect "comment_count" parameter, it required and type string or null';
    public const string INVALID_CARMA_COUNT   = 'Incorrect "carma_count" parameter, it required and type string or null';
}
