<?php

declare(strict_types=1);

namespace App\Domain\Rating\DTO\Genesis;

use WalkWeb\NW\AppException;

class GenesisRatingCollectionFactory
{
    /**
     * @param array $data
     * @return GenesisRatingCollection
     * @throws AppException
     */
    public static function create(array $data): GenesisRatingCollection
    {
        $collection = new GenesisRatingCollection();

        foreach ($data as $datum) {
            if (!is_array($datum)) {
                throw new AppException(GenesisRatingException::EXPECTED_ARRAY);
            }

            $collection->add(GenesisRatingFactory::create($datum));
        }

        return $collection;
    }
}
