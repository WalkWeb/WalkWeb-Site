<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\Domain\Post\PostRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class PostPageHandler extends AbstractHandler
{
    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        $repository = new PostRepository($this->container);

        $post = $repository->get($request->slug);

        if (!$post) {
            return $this->render(
                'errors/custom_404',
                ['error' => 'Пост не найден'],
                Response::NOT_FOUND
            );
        }

        $this->title = htmlspecialchars($post->getTitle()) . ' | ' . APP_NAME;
        $owner = false;

        if ($authorize = $this->container->exist('user')) {
            $user = $this->getUser();

            if ($post->getAuthor()->getId() === $user->getId()) {
                $owner = true;
            }
        }

        // TODO Проверка на то, лайкал ли пост авторизованный юзер

        return $this->render('post/index', [
            'post'      => $post,
            'authorize' => $authorize,
            'owner'     => $owner,
        ]);
    }
}
