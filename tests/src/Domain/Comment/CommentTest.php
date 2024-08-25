<?php

declare(strict_types=1);

namespace Test\src\Domain\Comment;

use App\Domain\Comment\CommentCollection;
use App\Domain\Comment\CommentFactory;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CommentTest extends AbstractTest
{
    /**
     * @dataProvider addChildrenDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testCommentAddChildren(array $data): void
    {
        $comment = CommentFactory::create($data[0]);
        $child1 = CommentFactory::create($data[1]);
        $child2 = CommentFactory::create($data[2]);
        $expectedCollection = new CommentCollection();
        $expectedCollection->add($child1);
        $expectedCollection->add($child2);

        $comment->addChildren($child1);
        $comment->addChildren($child2);

        self::assertEquals($expectedCollection, $comment->getChildren());
    }

    /**
     * @return array
     */
    public function addChildrenDataProvider(): array
    {
        return [
            [
                [
                    [
                        'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                        'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                        'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b01',
                        'guest_name'    => '',
                        'message'       => 'comment 1',
                        'approved'      => 1,
                        'parent_id'     => null,
                        'level'         => 0,
                        'likes'         => 0,
                        'dislikes'      => 0,
                        'user_reaction' => 0,
                        'author_name'   => 'DemoUser',
                        'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                        'author_level'  => 1,
                        'is_liked'      => true,
                        'created_at'    => '2024-06-16 16:00:00',
                        'updated_at'    => '2024-06-16 16:00:00',
                    ],
                    [
                        'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433402',
                        'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                        'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b04',
                        'guest_name'    => '',
                        'message'       => 'comment 2',
                        'approved'      => 1,
                        'parent_id'     => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                        'level'         => 0,
                        'likes'         => 3,
                        'dislikes'      => 1,
                        'user_reaction' => 1,
                        'author_name'   => 'NameModerator',
                        'author_avatar' => '/img/avatars/it/designer/female/01.jpg',
                        'author_level'  => 4,
                        'is_liked'      => true,
                        'created_at'    => '2024-06-17 16:00:00',
                        'updated_at'    => '2024-06-17 16:00:00',
                    ],
                    [
                        'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433403',
                        'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                        'author_id'     => null,
                        'guest_name'    => 'guest name',
                        'message'       => 'comment 3',
                        'approved'      => 1,
                        'parent_id'     => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                        'level'         => 0,
                        'likes'         => 0,
                        'dislikes'      => 1,
                        'user_reaction' => -1,
                        'author_name'   => null,
                        'author_avatar' => null,
                        'author_level'  => null,
                        'is_liked'      => true,
                        'created_at'    => '2024-06-18 16:00:00',
                        'updated_at'    => '2024-06-18 16:00:00',
                    ],
                ],
            ],
        ];
    }
}
