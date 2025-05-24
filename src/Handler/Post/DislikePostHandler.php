<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\Domain\Account\Carma\CarmaRepository;
use App\Domain\Post\PostRepository;
use App\Handler\AbstractHandler;
use App\Handler\Post\Traits\LikePostTrait;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class DislikePostHandler extends AbstractHandler
{
    use LikePostTrait;

    private PostRepository $postRepository;
    private CarmaRepository $carmaRepository;

    public function __construct(
        Container $container,
        ?PostRepository $postRepository = null,
        ?CarmaRepository $carmaRepository = null
    )
    {
        parent::__construct($container);
        $this->postRepository = $postRepository ?? new PostRepository($this->container);
        $this->carmaRepository = $carmaRepository ?? new CarmaRepository($this->container);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        if ($response = $this->validateRequest($request, $this->postRepository)) {
            return $response;
        }

        $slug = $request->slug;
        $this->postRepository->dislike($slug, $this->getUser()->getId(), 1);
        $this->carmaRepository->changeRating($this->postRepository->getAuthorId($slug), -1);

        return $this->json(['success' => true]);
    }
}
