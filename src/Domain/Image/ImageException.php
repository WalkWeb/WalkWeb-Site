<?php

declare(strict_types=1);

namespace App\Domain\Image;

use Exception;

class ImageException extends Exception
{
    public const INVALID_ID         = 'Incorrect "id" parameter, it required and type string (uuid)';
    public const INVALID_ACCOUNT_ID = 'Incorrect "account_id" parameter, it required and type string (uuid)';
    public const INVALID_NAME       = 'Incorrect "name" parameter, it required and type string';
    public const INVALID_FILE_PATH  = 'Incorrect "file_path" parameter, it required and type string';
    public const INVALID_SIZE       = 'Incorrect "size" parameter, it required and type int';
    public const INVALID_WIDTH      = 'Incorrect "width" parameter, it required and type int';
    public const INVALID_HEIGHT     = 'Incorrect "height" parameter, it required and type int';
    public const INVALID_CREATED_AT = 'Incorrect "created_at" parameter, it required and type string date';
}
