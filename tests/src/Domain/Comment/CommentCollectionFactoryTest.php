<?php

declare(strict_types=1);

namespace Test\src\Domain\Comment;

use App\Domain\Comment\CommentCollectionFactory;
use App\Domain\Comment\CommentException;
use App\Domain\Comment\CommentInterface;
use DateTime;
use Exception;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CommentCollectionFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws Exception
     */
    public function testCommentCollectionFactoryCreateSuccess(array $data): void
    {
        $comments = CommentCollectionFactory::create($data);

        self::assertSameSize($data, $comments);

        $i = 0;
        foreach ($comments as $comment) {
            self::assertEquals($data[$i]['id'], $comment->getId());
            self::assertEquals($data[$i]['post_id'], $comment->getPostId());
            self::assertEquals($data[$i]['message'], $comment->getMessage());
            self::assertEquals((bool)$data[$i]['approved'], $comment->isApproved());
            self::assertEquals($data[$i]['parent_id'], $comment->getParentId());
            self::assertEquals($data[$i]['level'], $comment->getLevel());
            self::assertEquals($data[$i]['likes'], $comment->getRating()->getLikes());
            self::assertEquals($data[$i]['dislikes'], $comment->getRating()->getDislikes());
            self::assertEquals($data[$i]['user_reaction'], $comment->getRating()->getUserReaction());

            if ($data[$i]['author_id'] !== null) {
                self::assertEquals($data[$i]['author_id'], $comment->getAuthorId());
                self::assertEquals($data[$i]['author_name'], $comment->getAuthorName());
                self::assertEquals($data[$i]['author_avatar'], $comment->getAuthorAvatar());
                self::assertEquals($data[$i]['author_level'], $comment->getAuthorLevel());
            } else {
                self::assertNull($comment->getAuthorId());
                self::assertEquals($data[$i]['guest_name'], $comment->getAuthorName());
                self::assertEquals(CommentInterface::DEFAULT_AVATAR, $comment->getAuthorAvatar());
                self::assertEquals(0, $comment->getAuthorLevel());
            }

            self::assertEquals(new DateTime($data[$i]['created_at']), $comment->getCreatedAt());
            self::assertEquals(new DateTime($data[$i]['updated_at']), $comment->getUpdatedAt());
            $i++;
        }
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     */
    public function testCommentCollectionFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        CommentCollectionFactory::create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
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
                        'parent_id'     => null,
                        'level'         => 0,
                        'likes'         => 3,
                        'dislikes'      => 1,
                        'user_reaction' => 1,
                        'author_name'   => 'NameModerator',
                        'author_avatar' => '/img/avatars/it/designer/female/01.jpg',
                        'author_level'  => 4,
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
                        'parent_id'     => null,
                        'level'         => 0,
                        'likes'         => 0,
                        'dislikes'      => 1,
                        'user_reaction' => -1,
                        'author_name'   => null,
                        'author_avatar' => null,
                        'author_level'  => null,
                        'created_at'    => '2024-06-18 16:00:00',
                        'updated_at'    => '2024-06-18 16:00:00',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function failDataProvider(): array
    {
        return [
            // double id
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
                        'created_at'    => '2024-06-16 16:00:00',
                        'updated_at'    => '2024-06-16 16:00:00',
                    ],
                    [
                        'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                        'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                        'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b04',
                        'guest_name'    => '',
                        'message'       => 'comment 2',
                        'approved'      => 1,
                        'parent_id'     => null,
                        'level'         => 0,
                        'likes'         => 3,
                        'dislikes'      => 1,
                        'user_reaction' => 1,
                        'author_name'   => 'NameModerator',
                        'author_avatar' => '/img/avatars/it/designer/female/01.jpg',
                        'author_level'  => 4,
                        'created_at'    => '2024-06-17 16:00:00',
                        'updated_at'    => '2024-06-17 16:00:00',
                    ],
                ],
                CommentException::ALREADY_EXIST,
            ],
            // no array data
            [
                [
                    'data',
                    [
                        'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                        'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                        'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b04',
                        'guest_name'    => '',
                        'message'       => 'comment 2',
                        'approved'      => 1,
                        'parent_id'     => null,
                        'level'         => 0,
                        'likes'         => 3,
                        'dislikes'      => 1,
                        'user_reaction' => 1,
                        'author_name'   => 'NameModerator',
                        'author_avatar' => '/img/avatars/it/designer/female/01.jpg',
                        'author_level'  => 4,
                        'created_at'    => '2024-06-17 16:00:00',
                        'updated_at'    => '2024-06-17 16:00:00',
                    ],
                ],
                CommentException::EXPECTED_ARRAY,
            ],
        ];
    }
}
