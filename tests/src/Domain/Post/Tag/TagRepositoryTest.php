<?php

declare(strict_types=1);

namespace Test\src\Domain\Post\Tag;

use App\Domain\Post\DTO\CreatePostRequestFactory;
use App\Domain\Post\Tag\TagFactory;
use App\Domain\Post\Tag\TagInterface;
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
        $data = $this->getDataByPostId($postId);

        self::assertSameSize($tags, $data);

        $i = 0;
        foreach ($tags as $tag) {
            self::assertEquals($data[$i]['id'], $tag->getId());
            self::assertEquals($data[$i]['name'], $tag->getName());
            self::assertEquals($data[$i]['slug'], $tag->getSlug());
            self::assertEquals($data[$i]['icon'], $tag->getIcon());
            self::assertEquals($data[$i]['preview_post_id'], $tag->getPreviewPostId());
            self::assertEquals($data[$i]['approved'], $tag->isApproved());
            $i++;
        }
    }

    /**
     * @dataProvider getByNameDataProvider
     * @param string $name
     * @throws AppException
     */
    public function testTagRepositoryGetByNameSuccess(string $name): void
    {
        $tag = $this->getRepository()->getByName($name);
        $data = $this->getDataByName($name);

        self::assertEquals($data['id'], $tag->getId());
        self::assertEquals($data['name'], $tag->getName());
        self::assertEquals($data['slug'], $tag->getSlug());
        self::assertEquals($data['icon'], $tag->getIcon());
        self::assertEquals($data['preview_post_id'], $tag->getPreviewPostId());
        self::assertEquals($data['approved'], $tag->isApproved());
    }

    /**
     * @throws AppException
     */
    public function testTagRepositoryGetByNameNotFound(): void
    {
        self::assertNull($this->getRepository()->getByName('abc'));
    }

    /**
     * @dataProvider saveDataProvider
     * @param TagInterface $tag
     * @throws AppException
     */
    public function testTagRepositoryAdd(TagInterface $tag): void
    {
        $this->getRepository()->add($tag);

        $tagDb = TagFactory::create($this->getDataByName($tag->getName()));

        self::assertEquals($tagDb->getId(), $tag->getId());
        self::assertEquals($tagDb->getName(), mb_strtolower($tag->getName()));
        self::assertEquals($tagDb->getSlug(), mb_strtolower($tag->getSlug()));
        self::assertEquals($tagDb->getIcon(), $tag->getIcon());
        self::assertEquals($tagDb->getPreviewPostId(), $tag->getPreviewPostId());
        self::assertEquals($tagDb->isApproved(), $tag->isApproved());
    }

    /**
     * @throws AppException
     */
    public function testTagRepositorySaveCollection(): void
    {
        // Вначале проверяем общее количество тегов
        $tags = $this->getDataAllTags();

        self::assertCount(5, $tags);

        // Запрос на создание поста, в котором один тег новый, другой уже существующий
        $request = CreatePostRequestFactory::create([
            'title'   => 'title',
            'content' => 'content',
            'tags'    => ['News', 'Новости'],
        ], $this->createUser());

        $tagCollection = $this->getRepository()->saveCollection($request);

        self::assertCount(2, $tagCollection);

        // Проверяем добавленный новый тег
        $tag = $this->getDataByName('Новости');

        self::assertEquals('новости', $tag['name']);
        self::assertEquals('', $tag['icon']);
        self::assertEquals(null, $tag['preview_post_id']);
        self::assertEquals(0, $tag['approved']);

        // Вначале проверяем, что общее количество тегов увеличилось на 1
        $tags = $this->getDataAllTags();

        self::assertCount(6, $tags);
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
     * @return array
     */
    public function getByNameDataProvider(): array
    {
        return [
            [
                'diablo 2',
            ],
            [
                'Blizzard',
            ],
            [
                'rpg',
            ],
            [
                'news',
            ],
            [
                'Path of exile',
            ],
        ];
    }

    /**
     * @return array
     * @throws AppException
     */
    public function saveDataProvider(): array
    {
        return [
            [
                TagFactory::create([
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                    'name'            => 'Новости',
                    'slug'            => 'Novosti-100',
                    'icon'            => 'icon-1.png',
                    'preview_post_id' => null,
                    'approved'        => 1,
                ]),
            ],
            [
                TagFactory::create([
                    'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f11',
                    'name'            => 'Work',
                    'slug'            => 'Work-300',
                    'icon'            => 'work-1.png',
                    'preview_post_id' => '7684ad22-613b-4c65-9bad-b7dfdd394c01',
                    'approved'        => 0,
                ]),
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
    private function getDataByPostId(string $postId): array
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

    /**
     * @param string $name
     * @return array
     * @throws AppException
     */
    private function getDataByName(string $name): array
    {
        return self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT `id`, `name`, `slug`, `icon`, `preview_post_id`, `approved` FROM `post_tags` WHERE `name` = ?',
            [['type' => 's', 'value' => $name]],
            true
        );
    }

    /**
     * @return array
     * @throws AppException
     */
    private function getDataAllTags(): array
    {
        return self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `post_tags`',
        );
    }
}
