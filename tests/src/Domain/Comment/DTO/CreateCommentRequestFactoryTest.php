<?php

declare(strict_types=1);

namespace Test\src\Domain\Comment\DTO;

use App\Domain\Comment\CommentException;
use App\Domain\Comment\DTO\CreateCommentRequestFactory;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CreateCommentRequestFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testCreateCommentRequestFactoryCreateSuccess(array $data): void
    {
        $request = CreateCommentRequestFactory::create($data);

        self::assertEquals($data['post_slug'], $request->getPostSlug());
        self::assertEquals($data['message'], $request->getMessage());
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     */
    public function testCreateCommentRequestFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        CreateCommentRequestFactory::create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'post_slug' => 'post_slug-123',
                    'message'   => 'comment message',
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
            // miss post_slug
            [
                [
                    'message'   => 'comment message',
                ],
                CommentException::INVALID_POST_SLUG,
            ],
            // post_slug invalid type
            [
                [
                    'post_slug' => null,
                    'message'   => 'comment message',
                ],
                CommentException::INVALID_POST_SLUG,
            ],
            // miss message
            [
                [
                    'post_slug' => 'post_slug-123',
                ],
                CommentException::INVALID_MESSAGE,
            ],
            // message invalid type
            [
                [
                    'post_slug' => 'post_slug-123',
                    'message'   => 123,
                ],
                CommentException::INVALID_MESSAGE,
            ],
        ];
    }
}
