<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\Domain\Account\Carma\CarmaRepository;
use App\Domain\Post\PostRepository;
use App\Handler\AbstractHandler;
use App\Handler\Post\Traits\LikePostTrait;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class LikePostHandler extends AbstractHandler
{
    use LikePostTrait;

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        $postRepository = new PostRepository($this->container);
        $carmaRepository = new CarmaRepository($this->container);

        if ($response = $this->validateRequest($request, $postRepository)) {
            return $response;
        }

        $slug = $request->slug;
        $postRepository->like($slug, $this->getUser()->getId(), 1);
        $carmaRepository->changeRating($postRepository->getAuthorId($slug), 1);

        // TODO Проверка необходимости изменения статуса поста
        // TODO Добавление опыта автору посту, в случае изменения статуса поста

        return $this->json(['success' => true]);
    }
}
