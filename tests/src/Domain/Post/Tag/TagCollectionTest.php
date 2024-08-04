<?php

declare(strict_types=1);

namespace Test\src\Domain\Post\Tag;

use App\Domain\Post\Tag\Tag;
use App\Domain\Post\Tag\TagCollection;
use App\Domain\Post\Tag\TagException;
use Test\AbstractTest;

class TagCollectionTest extends AbstractTest
{
    /**
     * Тест на успешное создание TagCollection
     *
     * @throws TagException
     */
    public function testTagCollectionCreateSuccess(): void
    {
        $collection = new TagCollection();

        self::assertCount(0, $collection);

        $tag1 = new Tag(
            '16cb7f25-37b6-49d0-bd0d-13cd75bc71f8',
            'новости',
            'novosti',
            'icon-1.png',
            '59f2a61c-09bb-4187-8cff-f4efa0557a30',
            true
        );
        $tag2 = new Tag(
            'ca6758e4-6087-49d7-b6c0-7c0c9f6ad79e',
            'статьи',
            'stati',
            'icon-2.png',
            '59f2a61c-09bb-4187-8cff-f4efa0557a30',
            false
        );

        $collection->add($tag1);
        $collection->add($tag2);

        self::assertCount(2, $collection);

        $i = 0;
        foreach ($collection as $tag) {
            if ($i === 0) {
                self::assertEquals($tag1, $tag);
            }
            if ($i === 1) {
                self::assertEquals($tag2, $tag);
            }
            $i++;
        }

        self::assertEquals(
            [
                [
                    'id'              => '16cb7f25-37b6-49d0-bd0d-13cd75bc71f8',
                    'name'            => 'новости',
                    'slug'            => 'novosti',
                    'icon'            => 'icon-1.png',
                    'preview_post_id' => '59f2a61c-09bb-4187-8cff-f4efa0557a30',
                    'approved'        => true,
                ],
                [
                    'id'              => 'ca6758e4-6087-49d7-b6c0-7c0c9f6ad79e',
                    'name'            => 'статьи',
                    'slug'            => 'stati',
                    'icon'            => 'icon-2.png',
                    'preview_post_id' => '59f2a61c-09bb-4187-8cff-f4efa0557a30',
                    'approved'        => false,
                ],
            ],
            $collection->toArray()
        );
    }

    /**
     * Тест на ситуацию, когда в коллекцию добавляется гет, который в ней уже существует
     *
     * @throws TagException
     */
    public function testNoticeCollectionDoubleNotice(): void
    {
        $collection = new TagCollection();

        $tag = new Tag(
            '16cb7f25-37b6-49d0-bd0d-13cd75bc71f8',
            'новости',
            'novosti',
            'icon-1.png',
            '59f2a61c-09bb-4187-8cff-f4efa0557a30',
            true
        );

        $collection->add($tag);

        $this->expectException(TagException::class);
        $this->expectExceptionMessage(TagException::ALREADY_EXIST);
        $collection->add($tag);
    }
}
