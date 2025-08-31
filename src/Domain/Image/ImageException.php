<?php

declare(strict_types=1);

namespace App\Domain\Image;

use Exception;

class ImageException extends Exception
{
    public const string INVALID_ID         = 'Incorrect "id" parameter, it required and type string (uuid)';
    public const string INVALID_ACCOUNT_ID = 'Incorrect "account_id" parameter, it required and type string (uuid)';
    public const string INVALID_NAME       = 'Incorrect "name" parameter, it required and type string';
    public const string INVALID_FILE_PATH  = 'Incorrect "file_path" parameter, it required and type string';
    public const string INVALID_SIZE       = 'Incorrect "size" parameter, it required and type int';
    public const string INVALID_WIDTH      = 'Incorrect "width" parameter, it required and type int';
    public const string INVALID_HEIGHT     = 'Incorrect "height" parameter, it required and type int';
    public const string INVALID_CREATED_AT = 'Incorrect "created_at" parameter, it required and type string date';
}
