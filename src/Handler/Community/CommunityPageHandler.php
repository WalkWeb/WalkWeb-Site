<?php

declare(strict_types=1);

namespace App\Handler\Community;

use App\Domain\Community\CommunityRepository;
use App\Domain\Post\PostRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class CommunityPageHandler extends AbstractHandler
{
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
        $repository = new CommunityRepository($this->container);
        $user = $this->container->exist('user') ? $this->getUser() : null;
        $community = $repository->get($request->slug, $user);

        if (!$community) {
            return $this->render(
                'errors/custom_404',
                ['error' => 'Сообщество не найдено'],
                Response::NOT_FOUND
            );
        }

        $postRepository = new PostRepository($this->container);


        return $this->render('community/index', [
            'community' => $community,
            'posts'     => $postRepository->getAll(0, 20, $user, $community->getSlug()),
        ]);
    }
}
