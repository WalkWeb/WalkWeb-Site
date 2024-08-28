<?php

declare(strict_types=1);

namespace App\Handler\Community;

use App\Domain\Community\CommunityException;
use App\Domain\Community\CommunityRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class JoinCommunityHandler extends AbstractHandler
{
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

        $repository = new CommunityRepository($this->container);

        $communityId = $repository->getId($request->slug);

        if (!$communityId) {
            return $this->json(['success' => false, 'error' => CommunityException::NOT_FOUND]);
        }

        $repository->join($this->getUser()->getId(), $communityId);

        return $this->json(['success' => true]);
    }
}
