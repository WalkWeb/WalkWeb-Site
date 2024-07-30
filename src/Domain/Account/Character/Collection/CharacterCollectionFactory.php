<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Collection;

use WalkWeb\NW\AppException;

class CharacterCollectionFactory
{
    /**
     * @param array $data
     * @return CharacterCollection
     * @throws AppException
     */
    public static function create(array $data): CharacterCollection
    {
        $collection = new CharacterCollection();

        foreach ($data as $datum) {
            if (!is_array($datum)) {
                throw new AppException(CharacterListException::EXPECTED_ARRAY);
            }

            $collection->add(CharacterListFactory::create($datum));
        }

        return $collection;
    }
}
