<?php

declare(strict_types=1);

namespace App\Handler\Comment;

use App\Domain\Account\Carma\CarmaRepository;
use App\Domain\Comment\CommentRepository;
use App\Handler\AbstractHandler;
use App\Handler\Comment\Traits\LikeCommentTrait;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class DislikeCommentHandler extends AbstractHandler
{
    use LikeCommentTrait;

    private CommentRepository $commentRepository;
    private CarmaRepository $carmaRepository;

    public function __construct(
        Container $container,
        ?CommentRepository $commentRepository = null,
        ?CarmaRepository $carmaRepository = null
    ) {
        parent::__construct($container);
        $this->commentRepository = $commentRepository ?? new CommentRepository($this->container);
        $this->carmaRepository = $carmaRepository ?? new CarmaRepository($this->container);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        if ($response = $this->validateRequest($request, $this->commentRepository)) {
            return $response;
        }

        $id = $request->id;
        $this->commentRepository->dislike($id, $this->getUser()->getId(), 1);

        // Если комментария написан от гостя - ничью карму менять не нужно
        if ($authorId = $this->commentRepository->getAuthorId($id)) {
            $this->carmaRepository->changeRating($authorId, -1);
        }

        return $this->json(['success' => true]);
    }
}
