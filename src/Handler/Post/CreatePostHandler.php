<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\Domain\Account\Energy\EnergyRepository;
use App\Domain\Post\DTO\CreatePostRequestFactory;
use App\Domain\Post\PostException;
use App\Domain\Post\PostFactory;
use App\Domain\Post\PostInterface;
use App\Domain\Post\PostRepository;
use App\Domain\Post\Tag\TagRepository;
use App\Handler\AbstractHandler;
use Exception;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class CreatePostHandler extends AbstractHandler
{
    public const NO_AUTH = 'No auth';

    /**
     * TODO Проверка кармы
     *
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        if (!$this->container->exist('user')) {
            return $this->json(['success' => false, 'error' => self::NO_AUTH]);
        }

        try {
            $user = $this->getUser();

            if ($user->getEnergy()->getEnergy() < PostInterface::CREATE_ENERGY_COST) {
                return $this->json([
                    'success' => false,
                    'error' => sprintf(PostException::NO_CREATE_ENERGY, PostInterface::CREATE_ENERGY_COST, $user->getEnergy()->getEnergy()),
                ]);
            }

            $data = $request->getBody();

            // TODO Mock
            $data['tags'] = [];

            $dto = CreatePostRequestFactory::create($data, $user);

            $tagRepository = new TagRepository($this->container);
            $postRepository = new PostRepository($this->container);
            $energyRepository = new EnergyRepository($this->container);

            $tags = $tagRepository->saveCollection($dto);
            $post = PostFactory::createNew($dto, $tags);
            $postRepository->add($post);

            $user->getEnergy()->editEnergy(-30);
            $energyRepository->save($user->getEnergy());

            return $this->json(['success' => true, 'slug' => $post->getSlug()]);

        } catch (Exception $e) {
            return $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}