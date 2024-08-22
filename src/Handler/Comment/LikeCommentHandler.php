<?php

declare(strict_types=1);

namespace App\Handler\Comment;

use App\Domain\Account\Carma\CarmaRepository;
use App\Domain\Comment\CommentRepository;
use App\Handler\AbstractHandler;
use App\Handler\Comment\Traits\LikeCommentTrait;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class LikeCommentHandler extends AbstractHandler
{
    use LikeCommentTrait;

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        $commentRepository = new CommentRepository($this->container);
        $carmaRepository = new CarmaRepository($this->container);

        if ($response = $this->validateRequest($request, $commentRepository)) {
            return $response;
        }

        $id = $request->id;
        $commentRepository->like($id, $this->getUser()->getId(), 1);

        // Если комментария написан от гостя - ничью карму менять не нужно
        if ($authorId = $commentRepository->getAuthorId($id)) {
            $carmaRepository->changeRating($authorId, 1);
        }

        return $this->json(['success' => true]);
    }
}
