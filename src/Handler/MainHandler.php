<?php

declare(strict_types=1);

namespace App\Handler;

use App\Domain\Post\PostRepository;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class MainHandler extends AbstractHandler
{
    public const OFFSET = 0;
    public const LIMIT  = 10;

    private PostRepository $postRepository;

    public function __construct(Container $container, ?PostRepository $postRepository = null)
    {
        parent::__construct($container);
        $this->postRepository = $postRepository ?? new PostRepository($this->container);
    }

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
                'posts' => $this->postRepository->getAll(
                    self::OFFSET,
                    self::LIMIT,
                    $this->container->exist('user') ? $this->getUser() : null
                ),
            ],
        );
    }
}
