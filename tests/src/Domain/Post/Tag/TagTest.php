<?php

declare(strict_types=1);

namespace Test\src\Domain\Post\Tag;

use App\Domain\Post\Tag\Tag;
use Test\AbstractTest;

class TagTest extends AbstractTest
{
    public function testTagCreate(): void
    {
        $id = '4f289da9-e9bf-4744-9a39-7c66b36734b4';
        $name = 'Программирование';
        $slug = 'programmirovaniye';
        $icon = 'icon.png';
        $previewPostId = '616b33e4-4700-4a8f-b648-76f2b5ec854c';
        $approved = true;

        $tag = new Tag($id, $name, $slug, $icon, $previewPostId, $approved);

        self::assertEquals($id, $tag->getId());
        self::assertEquals($name, $tag->getName());
        self::assertEquals($slug, $tag->getSlug());
        self::assertEquals($icon, $tag->getIcon());
        self::assertEquals($previewPostId, $tag->getPreviewPostId());
        self::assertEquals($approved, $tag->isApproved());

        self::assertEquals(
            [
                'id'              => $id,
                'name'            => $name,
                'slug'            => $slug,
                'icon'            => $icon,
                'preview_post_id' => $previewPostId,
                'approved'        => $approved,
            ],
            $tag->toArray()
        );
    }
}
