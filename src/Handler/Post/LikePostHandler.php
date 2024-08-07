<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\Domain\Post\PostException;
use App\Domain\Post\PostRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class LikePostHandler extends AbstractHandler
{
    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        if (!$this->container->exist('user')) {
            return $this->json(['success' => false, 'error' => PostException::ERROR_NO_AUTH]);
        }

        $user = $this->getUser();

        if (!$user->isCanLike()) {
            return $this->json(['success' => false, 'error' => PostException::ERROR_DONT_LIKE]);
        }

        $slug = $request->slug;
        $repository = new PostRepository($this->container);

        if ($repository->isOwner($slug, $user->getId())) {
            return $this->json(['success' => false, 'error' => PostException::ERROR_OWNER]);
        }

        if ($repository->existLiked($slug, $user->getId())) {
            return $this->json(['success' => false, 'error' => PostException::ERROR_ALREADY_LIKE]);
        }

        $repository->like($slug, $user->getId(), 1);

        // TODO Проверка необходимости изменения статуса поста
        // TODO Добавление опыта автору посту, в случае изменения статуса поста

        return $this->json(['success' => true]);
    }
}
