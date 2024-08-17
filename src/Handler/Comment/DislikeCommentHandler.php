<?php

declare(strict_types=1);

namespace App\Handler\Comment;

use App\Domain\Comment\CommentRepository;
use App\Handler\AbstractHandler;
use App\Handler\Comment\Traits\LikeCommentTrait;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class DislikeCommentHandler extends AbstractHandler
{
    use LikeCommentTrait;

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        $repository = new CommentRepository($this->container);

        if ($response = $this->validateRequest($request, $repository)) {
            return $response;
        }

        $repository->dislike($request->id, $this->getUser()->getId(), 1);

        return $this->json(['success' => true]);
    }
}
