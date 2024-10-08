<?php

declare(strict_types=1);

namespace App\Handler\Post\Traits;

use App\Domain\Post\PostException;
use App\Domain\Post\PostInterface;
use App\Domain\Post\PostRepository;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

trait LikePostTrait
{
    /**
     * 1. Проверка авторизации
     * 2. Проверка возможности лайкать посты/комментарии у пользователя (по умолчанию это можно делать сразу после
     * регистрации, но можно сделать и ограничения)
     * 3. Проверка длинны slug
     * 4. Проверка, что пользователь не лайкает свой же пост
     * 5. Проверка, что пользователь ранее не лайкал пост
     *
     * @param Request $request
     * @param PostRepository $repository
     * @return Response|null
     * @throws AppException
     */
    private function validateRequest(Request $request, PostRepository $repository): ?Response
    {
        if (!$this->container->exist('user')) {
            return $this->json(['success' => false, 'error' => PostException::ERROR_NO_AUTH]);
        }

        $user = $this->getUser();

        if (!$user->isCanLike()) {
            return $this->json(['success' => false, 'error' => PostException::ERROR_DONT_LIKE]);
        }

        $slug = $request->slug;
        $slugLength = mb_strlen($slug);

        if ($slugLength < PostInterface::SLUG_MIN_LENGTH || $slugLength > PostInterface::SLUG_MAX_LENGTH) {
            return $this->json(
                [
                    'success' => false,
                    'error' => PostException::INVALID_SLUG_LENGTH . PostInterface::SLUG_MIN_LENGTH . '-' . PostInterface::SLUG_MAX_LENGTH
                ]
            );
        }

        if ($repository->isOwner($slug, $user->getId())) {
            return $this->json(['success' => false, 'error' => PostException::ERROR_OWNER]);
        }

        if ($repository->existLiked($slug, $user->getId())) {
            return $this->json(['success' => false, 'error' => PostException::ERROR_ALREADY_LIKE]);
        }

        return null;
    }
}
