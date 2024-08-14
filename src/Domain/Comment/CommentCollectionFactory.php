<?php

declare(strict_types=1);

namespace App\Domain\Comment;

use WalkWeb\NW\AppException;

class CommentCollectionFactory
{
    /**
     * @param array $data
     * @return CommentCollection
     * @throws AppException
     */
    public static function create(array $data): CommentCollection
    {
        $comments = new CommentCollection();

        foreach ($data as $datum) {
            if (!is_array($datum)) {
                throw new AppException(CommentException::EXPECTED_ARRAY);
            }

            $comments->add(CommentFactory::create($datum));
        }

        return $comments;
    }
}
