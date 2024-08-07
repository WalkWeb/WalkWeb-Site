<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\Domain\Post\PostRepository;
use App\Handler\AbstractHandler;
use App\Handler\Post\Traits\LikePostTrait;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class DislikePostHandler extends AbstractHandler
{
    use LikePostTrait;

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        $repository = new PostRepository($this->container);

        if ($response = $this->validateRequest($request, $repository)) {
            return $response;
        }

        $repository->dislike($request->slug, $this->getUser()->getId(), 1);

        return $this->json(['success' => true]);
    }
}
