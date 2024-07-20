<?php

declare(strict_types=1);

namespace App\Domain\Account\Collection;

use App\Domain\Account\AccountException;
use WalkWeb\NW\AppException;

class AccountCollectionFactory
{
    /**
     * @param array $data
     * @return AccountCollection
     * @throws AccountException
     * @throws AppException
     */
    public static function create(array $data): AccountCollection
    {
        $collection = new AccountCollection();

        foreach ($data as $datum) {
            if (!is_array($datum)) {
                throw new AppException(AccountException::EXPECTED_ARRAY);
            }

            $collection->add(AccountListFactory::create($datum));
        }

        return $collection;
    }
}
