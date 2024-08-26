<?php

declare(strict_types=1);

namespace Test\src\Handler\Post;

use App\Domain\Account\AccountInterface;
use App\Domain\Post\PostException;
use App\Domain\Post\PostFactory;
use App\Domain\Post\Status\PostStatusInterface;
use App\Domain\Post\Tag\TagCollection;
use App\Handler\Post\PostPageHandler;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class PostPageHandlerTest extends AbstractTest
{
    /**
     * @dataProvider unauthorizedDataProvider
     * @param string $template
     * @param string $slug
     * @param string $title
     * @param string $comment
     * @param string|null $community
     * @throws AppException
     */
    public function testPostPageHandlerUnauthorizedSuccess(
        string $template,
        string $slug,
        string $title,
        string $comment,
        ?string $community
    ): void
    {
        $request = new Request(['REQUEST_URI' => "/p/$slug"]);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression("/$title/", $response->getBody());
        self::assertMatchesRegularExpression("/$comment/", $response->getBody());

        // view like icon
        self::assertMatchesRegularExpression('/9650/', $response->getBody());

        // view dislike icon
        self::assertMatchesRegularExpression('/9660/', $response->getBody());

        if ($community) {
            self::assertMatchesRegularExpression("/$community/", $response->getBody());
        }
    }

    /**
     * @dataProvider authorizedDataProvider
     * @param string $template
     * @param string $token
     * @param string $slug
     * @param string $title
     * @param string $comment
     * @param string|null $community
     * @throws AppException
     */
    public function testPostPageHandlerAuthorizedSuccess(
        string $template,
        string $token,
        string $slug,
        string $title,
        string $comment,
        ?string $community
    ): void
    {
        $request = new Request(['REQUEST_URI' => "/p/$slug"], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression("/$title/", $response->getBody());
        self::assertMatchesRegularExpression("/$comment/", $response->getBody());

        // view like icon
        self::assertMatchesRegularExpression('/9650/', $response->getBody());

        // view dislike icon
        self::assertMatchesRegularExpression('/9660/', $response->getBody());

        if ($community) {
            self::assertMatchesRegularExpression("/$community/", $response->getBody());
        }
    }

    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testPostPageHandlerOwnerSuccess(string $template): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(['REQUEST_URI' => '/p/slug-post-1-1000'], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Title post 1/', $response->getBody());

        // no view like icon
        self::assertDoesNotMatchRegularExpression('/9650/', $response->getBody());

        // no view dislike icon
        self::assertDoesNotMatchRegularExpression('/9660/', $response->getBody());
    }

    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testPostPageHandlerNotFound(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/p/aaa']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Пост не найден/', $response->getBody());
    }

    /**
     * @dataProvider invalidCommunityDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testPostPageHandlerGetCommunityInvalid(array $data): void
    {
        $handler = new PostPageHandler(self::getContainer());
        $post = PostFactory::create($data, new TagCollection());

        $this->expectException(AppException::class);
        $this->expectExceptionMessage(PostException::INVALID_COMMUNITY . $post->getCommunitySlug());
        $handler->getCommunity($post);
    }

    /**
     * @return array
     */
    public function unauthorizedDataProvider(): array
    {
        return [
            [
                'default',
                'slug-post-2-1000',
                'Title post 2',
                'comment 3',
                null,
            ],
            [
                'inferno',
                'slug-post-2-1000',
                'Title post 2',
                'comment 3',
                null,
            ],
            [
                'inferno',
                'slug-post-5-1000',
                'Title post 5',
                'comment 41',
                'Diablo 2: База знаний',
            ],
        ];
    }

    /**
     * @return array
     */
    public function authorizedDataProvider(): array
    {
        return [
            [
                'default',
                'VBajfT8P6PFtrkHhCqb7ZNwIFG45a7',
                'slug-post-2-1000',
                'Title post 2',
                'comment 3',
                null,
            ],
            [
                'inferno',
                'VBajfT8P6PFtrkHhCqb7ZNwIFG45a7',
                'slug-post-2-1000',
                'Title post 2',
                'comment 3',
                null,
            ],
            [
                'inferno',
                'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                'slug-post-5-1000',
                'Title post 5',
                'comment 41',
                'Diablo 2: База знаний',
            ],
        ];
    }

    /**
     * @return array
     */
    public function invalidCommunityDataProvider(): array
    {
        return [
            [
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => 2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'is_liked'         => true,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'community_slug'   => 'community_slug',
                    'community_name'   => 'community_name',
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                ],
            ],
        ];
    }
}
