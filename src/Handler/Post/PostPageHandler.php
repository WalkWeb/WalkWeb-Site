<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\Domain\Comment\CommentCollection;
use App\Domain\Comment\CommentRepository;
use App\Domain\Post\PostRepository;
use App\Handler\AbstractHandler;
use DateTimeInterface;
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
        $this->layoutUrl = 'layout/index.php';

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

        if ($post->getCommentsCount() > 0) {
            $commentRepository = new CommentRepository($this->container);
            $comments = $commentRepository->getByPost($post->getId());
        } else {
            $comments = new CommentCollection();
        }

        $this->title = htmlspecialchars($post->getTitle()) . ' | ' . APP_NAME;

        return $this->render('post/index', [
            'post'     => $post,
            'comments' => $comments,
            'auth'     => $this->container->exist('user'),
        ]);
    }

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function getCreatedAtEasyData(DateTimeInterface $date): string
    {
        return self::getElapsedTime($date);
    }
}
