<?php

declare(strict_types=1);

namespace App\Domain\Community;

use WalkWeb\NW\AppException;

class CommunityCollectionFactory
{
    /**
     * @param array $data
     * @return CommunityCollection
     * @throws AppException
     */
    public static function create(array $data): CommunityCollection
    {
        $collection = new CommunityCollection();

        foreach ($data as $datum) {
            if (!is_array($datum)) {
                throw new AppException(CommunityException::EXPECTED_ARRAY);
            }

            $collection->add(CommunityFactory::create($datum));
        }

        return $collection;
    }
}
