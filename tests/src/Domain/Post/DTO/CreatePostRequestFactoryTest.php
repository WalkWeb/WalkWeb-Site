<?php

declare(strict_types=1);

namespace Test\src\Domain\Post\DTO;

use App\Domain\Post\DTO\CreatePostRequestFactory;
use App\Domain\Post\PostException;
use App\Domain\Post\PostInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CreatePostRequestFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testCreatePostRequestFactoryCreateSuccess(array $data): void
    {
        $user = $this->createUser();
        $request = CreatePostRequestFactory::create($data, $user);

        self::assertEquals($data['title'], $request->getTitle());
        self::assertEquals($data['content'], $request->getContent());
        self::assertEquals($data['tags'], $request->getTags());
        self::assertEquals($user, $request->getAuthor());
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     * @throws AppException
     */
    public function testCreatePostRequestFactoryCreateFail(array $data, string $error): void
    {
        $user = $this->createUser();
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        CreatePostRequestFactory::create($data, $user);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'title'   => 'title',
                    'content' => 'content',
                    'tags'    => ['tag-1', 'tag-2', 'tag-3'],
                ],
            ],
        ];
    }

    /**
     * @return array
     * @throws AppException
     */
    public function failDataProvider(): array
    {
        return [
            // miss title
            [
                [
                    'content' => 'content',
                    'tags'    => ['tag-1', 'tag-2', 'tag-3'],
                ],
                PostException::INVALID_TITLE,
            ],
            // title invalid type
            [
                [
                    'title'   => null,
                    'content' => 'content',
                    'tags'    => ['tag-1', 'tag-2', 'tag-3'],
                ],
                PostException::INVALID_TITLE,
            ],
            // title over min length
            [
                [
                    'title'   => self::generateString(PostInterface::TITLE_MIN_LENGTH - 1),
                    'content' => 'content',
                    'tags'    => ['tag-1', 'tag-2', 'tag-3'],
                ],
                PostException::INVALID_TITLE_LENGTH . PostInterface::TITLE_MIN_LENGTH . '-' . PostInterface::TITLE_MAX_LENGTH,
            ],
            // title over max length
            [
                [
                    'title'   => self::generateString(PostInterface::TITLE_MAX_LENGTH + 1),
                    'content' => 'content',
                    'tags'    => ['tag-1', 'tag-2', 'tag-3'],
                ],
                PostException::INVALID_TITLE_LENGTH . PostInterface::TITLE_MIN_LENGTH . '-' . PostInterface::TITLE_MAX_LENGTH,
            ],
            // miss content
            [
                [
                    'title' => 'title',
                    'tags'  => ['tag-1', 'tag-2', 'tag-3'],
                ],
                PostException::INVALID_CONTENT,
            ],
            // content invalid type
            [
                [
                    'title'   => 'title',
                    'content' => 100,
                    'tags'    => ['tag-1', 'tag-2', 'tag-3'],
                ],
                PostException::INVALID_CONTENT,
            ],
            // content over min length
            [
                [
                    'title'   => 'title',
                    'content' => self::generateString(PostInterface::CONTENT_MIN_LENGTH - 1),
                    'tags'    => ['tag-1', 'tag-2', 'tag-3'],
                ],
                PostException::INVALID_CONTENT_LENGTH . PostInterface::CONTENT_MIN_LENGTH . '-' . PostInterface::CONTENT_MAX_LENGTH,
            ],
            // content over max length
            [
                [
                    'title'   => 'title',
                    'content' => self::generateString(PostInterface::CONTENT_MAX_LENGTH + 1),
                    'tags'    => ['tag-1', 'tag-2', 'tag-3'],
                ],
                PostException::INVALID_CONTENT_LENGTH . PostInterface::CONTENT_MIN_LENGTH . '-' . PostInterface::CONTENT_MAX_LENGTH,
            ],
            // miss tags
            [
                [
                    'title'   => 'title',
                    'content' => 'content',
                ],
                PostException::INVALID_TAGS,
            ],
            // tags invalid type
            [
                [
                    'title'   => 'title',
                    'content' => 'content',
                    'tags'    => true,
                ],
                PostException::INVALID_TAGS,
            ],
            // tag no string
            [
                [
                    'title'   => 'title',
                    'content' => 'content',
                    'tags'    => ['tag-1', 19.4, 'tag-3'],
                ],
                PostException::INVALID_TAG,
            ],
        ];
    }
}
