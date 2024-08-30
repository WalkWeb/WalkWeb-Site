<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\Domain\Community\CommunityRepository;
use App\Domain\Post\PostInterface;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class CreatePostPageHandler extends AbstractHandler
{
    private CommunityRepository $communityRepository;

    public function __construct(Container $container, ?CommunityRepository $communityRepository = null)
    {
        parent::__construct($container);
        $this->communityRepository = $communityRepository ?? new CommunityRepository($this->container);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        if ($loginResponse = $this->checkAuth($request)) {
            return $loginResponse;
        }

        $slug = $request->slug;

        if ($slug === PostInterface::NO_COMMUNITY) {
            return $this->render('post/create', [
                'user'          => $this->getUser(),
                'communitySlug' => PostInterface::NO_COMMUNITY,
                'communityName' => PostInterface::NO_COMMUNITY,
            ]);
        }

        if (!$name = $this->communityRepository->getName($slug)) {
            return $this->render('errors/no_create_community', [], Response::NOT_FOUND);
        }

        return $this->render('post/create', [
            'user'          => $this->getUser(),
            'communitySlug' => $slug,
            'communityName' => $name,
        ]);
    }
}
