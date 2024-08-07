<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\Domain\Post\PostInterface;
use App\Domain\Post\PostRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;
use WalkWeb\NW\Traits\DateTrait;

class PostPageHandler extends AbstractHandler
{
    use DateTrait;

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        $repository = new PostRepository($this->container);
        $user = $this->container->exist('user') ? $this->getUser() : null;
        $post = $repository->get($request->getAttribute('slug'), $user);

        if (!$post) {
            return $this->render(
                'errors/custom_404',
                ['error' => 'Пост не найден'],
                Response::NOT_FOUND
            );
        }

        $this->title = htmlspecialchars($post->getTitle()) . ' | ' . APP_NAME;

        return $this->render('post/index', ['post' => $post]);
    }

    /**
     * @param PostInterface $post
     * @return string
     */
    protected function getCreatedAtEasyData(PostInterface $post): string
    {
        return self::getElapsedTime($post->getCreatedAt());
    }
}
