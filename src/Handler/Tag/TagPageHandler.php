<?php

declare(strict_types=1);

namespace App\Handler\Tag;

use App\Domain\Post\PostRepository;
use App\Domain\Post\Tag\TagException;
use App\Domain\Post\Tag\TagInterface;
use App\Domain\Post\Tag\TagRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class TagPageHandler extends AbstractHandler
{
    // TODO Вынести функционал фильтров в отдельный класс, т.к. будет применяться не только в тегах

    public const BEST_POST    = 'best';

    public const FILTER_ALL   = 'all';
    public const FILTER_TREND = 'trend';
    public const FILTER_HOT   = 'hot';
    public const FILTER_TOP   = 'top';

    public const RATING_ALL   = -10;
    public const RATING_TREND = 3;
    public const RATING_HOT   = 5;
    public const RATING_TOP   = 10;

    public const LIMIT  = 10;
    public const OFFSET = 0;

    private static array $ratings = [
        self::FILTER_ALL   => self::RATING_ALL,
        self::FILTER_TREND => self::RATING_TREND,
        self::FILTER_HOT   => self::RATING_HOT,
        self::FILTER_TOP   => self::RATING_TOP,
    ];

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        $this->layoutUrl = 'layout/index.php';

        $slug = $request->slug;
        $rating = $request->rating;
        $slugLength = mb_strlen($slug);

        if ($slugLength < TagInterface::SLUG_MIN_LENGTH || $slugLength > TagInterface::SLUG_MAX_LENGTH) {
            throw new AppException(
                TagException::INVALID_SLUG_LENGTH . TagInterface::SLUG_MIN_LENGTH . '-' . TagInterface::SLUG_MAX_LENGTH
            );
        }

        if ($rating !== self::BEST_POST && !array_key_exists($rating, self::$ratings)) {
            return $this->render(
                'errors/custom_404',
                ['error' => TagException::UNKNOWN_RATING],
                Response::NOT_FOUND
            );
        }

        if ($rating === self::BEST_POST) {
            $minRating = self::$ratings['all'];
            $best = true;
        } else {
            $minRating = self::$ratings[$rating];
            $best = false;
        }

        $tagRepository = new TagRepository($this->container);
        $postRepository = new PostRepository($this->container);

        $tag = $tagRepository->getBySlug($slug);

        if (!$tag) {
            return $this->render(
                'errors/custom_404',
                ['error' => TagException::NOT_FOUND],
                Response::NOT_FOUND
            );
        }

        $user = $this->container->exist('user') ? $this->getUser() : null;

        return $this->render('tag/index', [
            'tag'    => $tag,
            'rating' => $rating,
            'posts'  => $postRepository->getPostByTag($slug, self::OFFSET, self::LIMIT, $minRating, $best, $user),
        ]);
    }
}
