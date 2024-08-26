<?php

declare(strict_types=1);

namespace Test\src\Domain\Community;

use App\Domain\Community\BlankCommunity;
use DateTime;
use Test\AbstractTest;

class BlankCommunityTest extends AbstractTest
{
    public function testBlankCommunity(): void
    {
        $community = new BlankCommunity();

        self::assertEquals('', $community->getId());
        self::assertEquals(0, $community->getLevel());
        self::assertEquals('', $community->getName());
        self::assertEquals('', $community->getSlug());
        self::assertEquals('', $community->getDescription());
        self::assertEquals('', $community->getIcon());
        self::assertEquals('', $community->getIconSmall());
        self::assertEquals('', $community->getHeadImage());
        self::assertEquals(0, $community->getFollowers());
        self::assertEquals(null, $community->getFixedPostId());
        self::assertEquals(null, $community->getMenu());
        self::assertEquals('', $community->getOwnerId());
        self::assertEquals(0, $community->getTotalPostCount());
        self::assertEquals(0, $community->getSilverPostCount());
        self::assertEquals(0, $community->getGoldPostCount());
        self::assertEquals(0, $community->getDiamondPostCount());
        self::assertEquals(0, $community->getTotalCommentCount());
        self::assertEquals((new DateTime())->format(self::DATE_FORMAT), $community->getCreatedAt()->format(self::DATE_FORMAT));
        self::assertEquals((new DateTime())->format(self::DATE_FORMAT), $community->getUpdatedAt()->format(self::DATE_FORMAT));
    }
}
