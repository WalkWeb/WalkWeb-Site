<?php

declare(strict_types=1);

namespace App\Handler;

use App\Domain\Post\Collection\PostListInterface;
use App\Domain\Post\PostRepository;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;
use WalkWeb\NW\Traits\DateTrait;

class MainHandler extends AbstractHandler
{
    use DateTrait;

    public const OFFSET = 0;
    public const LIMIT  = 10;

    /**
     * Print main page
     *
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        $this->layoutUrl = 'layout/index.php';

        return $this->render(
            'index',
            [
                'posts' => (new PostRepository($this->container))->getCollection(
                    self::OFFSET,
                    self::LIMIT,
                    $this->container->exist('user') ? $this->getUser() : null
                ),
            ],
        );
    }

    /**
     * @param PostListInterface $post
     * @return string
     */
    protected function getCreatedAtEasyData(PostListInterface $post): string
    {
        return self::getElapsedTime($post->getCreatedAt());
    }
}
