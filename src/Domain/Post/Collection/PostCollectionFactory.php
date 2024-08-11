<?php

declare(strict_types=1);

namespace App\Domain\Post\Collection;

use App\Domain\Post\PostException;
use WalkWeb\NW\AppException;

class PostCollectionFactory
{
    /**
     * @param array $data
     * @return PostCollection
     * @throws AppException
     */
    public static function create(array $data): PostCollection
    {
        $posts = new PostCollection();

        foreach ($data as $datum) {
            if (!is_array($datum)) {
                throw new AppException(PostException::EXPECTED_ARRAY);
            }

            $posts->add(PostListFactory::create($datum));
        }

        return $posts;
    }
}
