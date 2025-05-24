<?php

declare(strict_types=1);

namespace App\Handler\Rating;

use App\Domain\Rating\Rating;
use App\Domain\Rating\RatingRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountCarmaRatingPageHandler extends AbstractHandler
{
    private Rating $rating;

    public function __construct(Container $container, ?Rating $rating = null)
    {
        parent::__construct($container);
        $this->rating = $rating ?? (new Rating(new RatingRepository($this->container)));
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        return $this->render('rating/account_carma', [
            'accounts' => $this->rating->getTopAccountCarma(),
        ]);
    }
}
