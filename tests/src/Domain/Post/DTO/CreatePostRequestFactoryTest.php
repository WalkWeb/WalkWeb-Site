<?php

declare(strict_types=1);

namespace Test\src\Domain\Post\DTO;

use App\Domain\Account\Notice\NoticeCollection;
use App\Domain\Auth\AuthFactory;
use App\Domain\Auth\AuthInterface;
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

    /**
     * @return AuthInterface
     * @throws AppException
     */
    private function createUser(): AuthInterface
    {
        return AuthFactory::create(
            [
                'id'                => '68435c80-eb31-4756-a260-a00900e5db9f',
                'name'              => 'AccountName',
                'avatar'            => 'account_avatar.png',
                'verified_token'    => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                'main_character_id' => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
                'account_group_id'  => 10,
                'account_status_id' => 1,
                'energy'            => [
                    'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                    'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'energy'            => 30,
                    'energy_bonus'      => 15,
                    'energy_updated_at' => 1566745426.0000,
                    'energy_residue'    => 10,
                ],
                'can_like'          => 1,
                'level'             => [
                    'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'character_stat_points' => 0,
                ],
                'template'          => 'default',
                'email_verified'    => 0,
                'upload'            => 1000,
                'upload_bonus'      => 3,
            ],
            $this->getSendNoticeAction(),
            new NoticeCollection(),
        );
    }
}
