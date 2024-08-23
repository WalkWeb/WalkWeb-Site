<?php

declare(strict_types=1);

namespace Test\src\Domain\Rating;

use App\Domain\Rating\Rating;
use App\Domain\Rating\RatingInterface;
use App\Domain\Rating\RatingRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class RatingTest extends AbstractTest
{
    /**
     * @throws AppException
     */
    public function testRatingGetTopAccountLevel(): void
    {
        $users = $this->getRating()->getTopAccountLevel();

        self::assertCount(14, $users);

        $i = 0;
        foreach ($users as $user) {
            // Проверяем первую тройку
            if ($i === 0) {
                self::assertEquals(4, $user->getLevel());
                self::assertEquals(450, $user->getExp());
            }
            if ($i === 1) {
                self::assertEquals(3, $user->getLevel());
                self::assertEquals(150, $user->getExp());
            }
            if ($i === 2) {
                self::assertEquals(2, $user->getLevel());
                self::assertEquals(54, $user->getExp());
            }
            $i++;
        }

    }

    /**
     * @return RatingInterface
     * @throws AppException
     */
    private function getRating(): RatingInterface
    {
        return new Rating(new RatingRepository(self::getContainer()));
    }
}
