<?php

declare(strict_types=1);

namespace App\Handler\Community;

use App\Domain\Community\CommunityException;
use App\Domain\Community\CommunityRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class JoinCommunityHandler extends AbstractHandler
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
        if (!$this->container->exist('user')) {
            return $this->json(['success' => false, 'error' => self::NO_AUTH]);
        }

        // TODO validation slug

        $communityId = $this->communityRepository->getId($request->slug);

        if (!$communityId) {
            return $this->json(['success' => false, 'error' => CommunityException::NOT_FOUND]);
        }

        $this->communityRepository->join($this->getUser()->getId(), $communityId);

        return $this->json(['success' => true]);
    }
}
