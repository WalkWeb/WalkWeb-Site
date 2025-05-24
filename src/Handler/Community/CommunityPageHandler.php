<?php

declare(strict_types=1);

namespace App\Handler\Community;

use App\Domain\Community\CommunityRepository;
use App\Domain\Post\PostRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class CommunityPageHandler extends AbstractHandler
{
    private const PER_PAGE = 20;

    private CommunityRepository $communityRepository;
    private PostRepository $postRepository;

    public function __construct(
        Container $container,
        ?CommunityRepository $communityRepository = null,
        ?PostRepository $postRepository = null
    )
    {
        parent::__construct($container);
        $this->communityRepository = $communityRepository ?? new CommunityRepository($this->container);
        $this->postRepository = $postRepository ?? new PostRepository($this->container);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        // TODO slug validate

        // TODO posts

        // TODO rating post filter

        $this->layoutUrl = 'layout/index.php';
        $user = $this->container->exist('user') ? $this->getUser() : null;
        $community = $this->communityRepository->get($request->slug, $user);

        if (!$community) {
            return $this->render(
                'errors/custom_404',
                ['error' => 'Сообщество не найдено'],
                Response::NOT_FOUND
            );
        }

        return $this->render('community/index', [
            'community' => $community,
            'posts'     => $this->postRepository->getAll(0, self::PER_PAGE, $user, $community->getSlug()),
        ]);
    }
}
