<?php

declare(strict_types=1);

namespace Test\src\Domain\Post\Tag;

use App\Domain\Post\Tag\TagRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class TagRepositoryTest extends AbstractTest
{
    /**
     * @dataProvider getByPostIdDataProvider
     * @param string $postId
     * @throws AppException
     */
    public function testTagRepositoryGetByPostId(string $postId): void
    {
        $tags = $this->getRepository()->getByPostId($postId);
        $data = $this->getData($postId);

        self::assertSameSize($tags, $data);

        $i = 0;
        foreach ($tags as $tag) {
            self::assertEquals($data[$i]['id'], $tag->getId());
            self::assertEquals($data[$i]['name'], $tag->getName());
            self::assertEquals($data[$i]['slug'], $tag->getSlug());
            self::assertEquals($data[$i]['icon'], $tag->getIcon());
            self::assertEquals($data[$i]['preview_post_id'] ?? '', $tag->getPreviewPostId());
            self::assertEquals($data[$i]['approved'], $tag->isApproved());
            $i++;
        }
    }

    /**
     * @return array
     */
    public function getByPostIdDataProvider(): array
    {
        return [
            [
                '7684ad22-613b-4c65-9bad-b7dfdd394c01',
            ],
            [
                '7684ad22-613b-4c65-9bad-b7dfdd394c02',
            ],
            [
                '7684ad22-613b-4c65-9bad-b7dfdd394c03',
            ],
        ];
    }

    /**
     * @return TagRepository
     * @throws AppException
     */
    private function getRepository(): TagRepository
    {
        return new TagRepository(self::getContainer());
    }

    /**
     * @param string $postId
     * @return array
     * @throws AppException
     */
    private function getData(string $postId): array
    {
        return self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT 

                `post_tags`.`id`,
                `post_tags`.`name`,
                `post_tags`.`slug`,
                `post_tags`.`icon`,
                `post_tags`.`preview_post_id`,
                `post_tags`.`approved`,
                `post_tags`.`created_at`
                
                FROM `lk_post_tag`

                JOIN `post_tags` on `lk_post_tag`.`tag_id` = `post_tags`.`id`

                WHERE `lk_post_tag`.`post_id` = ?',
            [['type' => 's', 'value' => $postId]],
        );
    }
}
