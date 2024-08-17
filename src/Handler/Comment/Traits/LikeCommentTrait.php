<?php

declare(strict_types=1);

namespace App\Handler\Comment\Traits;

use App\Domain\Comment\CommentException;
use App\Domain\Comment\CommentRepository;
use Ramsey\Uuid\Uuid;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

trait LikeCommentTrait
{
    /**
     * @param Request $request
     * @param CommentRepository $repository
     * @return Response|null
     * @throws AppException
     */
    private function validateRequest(Request $request, CommentRepository $repository): ?Response
    {
        if (!$this->container->exist('user')) {
            return $this->json(['success' => false, 'error' => CommentException::ERROR_NO_AUTH]);
        }

        $user = $this->getUser();

        if (!$user->isCanLike()) {
            return $this->json(['success' => false, 'error' => CommentException::ERROR_DONT_LIKE]);
        }

        $id = $request->id;

        if (!Uuid::isValid($id)) {
            return $this->json(['success' => false, 'error' => CommentException::INVALID_ID]);
        }

        if ($repository->isOwner($id, $user->getId())) {
            return $this->json(['success' => false, 'error' => CommentException::ERROR_OWNER]);
        }

        if ($repository->existLiked($id, $user->getId())) {
            return $this->json(['success' => false, 'error' => CommentException::ERROR_ALREADY_LIKE]);
        }

        return null;
    }
}
