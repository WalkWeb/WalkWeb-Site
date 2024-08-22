<?php

declare(strict_types=1);

namespace App\Handler\Rating;

use App\Domain\Account\Character\Genesis\GenesisRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class GenesisRatingPageHandler extends AbstractHandler
{
    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        return $this->render('rating/genesis', [
            'top' => (new GenesisRepository($this->container))->getTop(THEME),
        ]);
    }
}
