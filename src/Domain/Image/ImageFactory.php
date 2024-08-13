<?php

declare(strict_types=1);

namespace App\Domain\Image;

use App\Domain\Auth\AuthInterface;
use DateTime;
use Ramsey\Uuid\Uuid;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Loader\Image as NWImage;
use WalkWeb\NW\Traits\ValidationTrait;

class ImageFactory
{
    use ValidationTrait;

    /**
     * @param array $data
     * @return ImageInterface
     * @throws AppException
     */
    public static function create(array $data): ImageInterface
    {
        return new Image(
            self::uuid($data, 'id', ImageException::INVALID_ID),
            self::uuid($data, 'account_id', ImageException::INVALID_ACCOUNT_ID),
            self::string($data, 'name', ImageException::INVALID_NAME),
            self::string($data, 'file_path', ImageException::INVALID_FILE_PATH),
            self::int($data, 'size', ImageException::INVALID_SIZE),
            self::int($data, 'width', ImageException::INVALID_WIDTH),
            self::int($data, 'height', ImageException::INVALID_HEIGHT),
            self::date($data, 'created_at', ImageException::INVALID_CREATED_AT),
        );
    }

    /**
     * @param NWImage $image
     * @param AuthInterface $user
     * @return ImageInterface
     */
    public static function createNew(NWImage $image, AuthInterface $user): ImageInterface
    {
        return new Image(
            Uuid::uuid4()->toString(),
            $user->getId(),
            $image->getName(),
            $image->getFilePath(),
            $image->getSize(),
            $image->getWidth(),
            $image->getHeight(),
            new DateTime(),
        );
    }
}
