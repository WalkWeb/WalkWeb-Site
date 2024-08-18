<?php

declare(strict_types=1);

namespace App\Handler\Tag;

use App\Domain\Post\PostRepository;
use App\Domain\Post\Tag\TagRepository;
use App\Handler\AbstractHandler;
use DateTimeInterface;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;
use WalkWeb\NW\Traits\DateTrait;

class TagPageHandler extends AbstractHandler
{
    use DateTrait;

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        $this->layoutUrl = 'layout/index.php';

        $slug = $request->slug;

        // TODO Валидация slug

        $tagRepository = new TagRepository($this->container);
        $postRepository = new PostRepository($this->container);

        $tag = $tagRepository->getBySlug($slug);

        if (!$tag) {
            return $this->render(
                'errors/custom_404',
                ['error' => 'Тег не найден'],
                Response::NOT_FOUND
            );
        }

        $user = $this->container->exist('user') ? $this->getUser() : null;

        return $this->render('tag/index', [
            'tag'   => $tag,
            'posts' => $postRepository->getPostByTag($slug, 0, 10, $user),
        ]);
    }

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function getCreatedAtEasyData(DateTimeInterface $date): string
    {
        return self::getElapsedTime($date);
    }
}
