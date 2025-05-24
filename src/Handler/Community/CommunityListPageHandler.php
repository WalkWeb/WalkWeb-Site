<?php

declare(strict_types=1);

namespace App\Handler\Community;

use App\Domain\Community\CommunityRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class CommunityListPageHandler extends AbstractHandler
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
        $this->layoutUrl = 'layout/index.php';
        return $this->render('community/list', [
            'communities' => $this->communityRepository->getAll(),
        ]);
    }
}
