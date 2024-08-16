<?php

declare(strict_types=1);

namespace App\Domain\Comment\DTO;

use App\Domain\Comment\CommentException;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class CreateCommentRequestFactory
{
    use ValidationTrait;

    /**
     * @param array $data
     * @return CreateCommentRequest
     * @throws AppException
     */
    public static function create(array $data): CreateCommentRequest
    {
        return new CreateCommentRequest(
            self::string($data, 'post_slug', CommentException::INVALID_POST_SLUG),
            self::string($data, 'message', CommentException::INVALID_MESSAGE),
        );
    }
}
