<?php

declare(strict_types=1);

namespace App\Domain\Post\Tag;

use WalkWeb\NW\AppException;

class TagCollectionFactory
{
    /**
     * @param array $data
     * @return TagCollection
     * @throws AppException
     */
    public static function create(array $data): TagCollection
    {
        $collection = new TagCollection();

        foreach ($data as $datum) {
            if (!is_array($datum)) {
                throw new AppException(TagException::EXPECTED_ARRAY);
            }

            $collection->add(TagFactory::create($datum));
        }

        return $collection;
    }
}
