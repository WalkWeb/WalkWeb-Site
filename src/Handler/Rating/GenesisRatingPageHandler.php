<?php

declare(strict_types=1);

namespace App\Handler\Rating;

use App\Domain\Account\Character\Genesis\GenesisRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class GenesisRatingPageHandler extends AbstractHandler
{
    private GenesisRepository $genesisRepository;

    public function __construct(Container $container, ?GenesisRepository $genesisRepository = null)
    {
        parent::__construct($container);
        $this->genesisRepository = $genesisRepository ?? new GenesisRepository($this->container);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        return $this->render('rating/genesis', [
            'top' => $this->genesisRepository->getTop(THEME),
        ]);
    }
}
