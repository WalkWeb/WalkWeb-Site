<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\Domain\Post\DTO\CreatePostRequestFactory;
use App\Domain\Post\PostFactory;
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
     * TODO Проверка наличие энергии
     *
     * TODO Проверка кармы
     *
     * TODO Уменьшение энергии
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
            $data = $request->getBody();

            // TODO Mock
            $data['tags'] = [];

            $dto = CreatePostRequestFactory::create($data, $this->getUser());

            $tagRepository = new TagRepository($this->container);
            $postRepository = new PostRepository($this->container);

            $tags = $tagRepository->saveCollection($dto);
            $post = PostFactory::createNew($dto, $tags);
            $postRepository->add($post);

            return $this->json(['success' => true, 'slug' => $post->getSlug()]);

        } catch (Exception $e) {
            return $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
