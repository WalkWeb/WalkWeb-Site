<?php

declare(strict_types=1);

namespace App\Handler\Temporary;

use App\Domain\Account\Energy\EnergyException;
use App\Domain\Account\Energy\EnergyRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class ReducedEnergyHandler extends AbstractHandler
{
    private const ADD_ENERGY = -40;

    private EnergyRepository $energyRepository;

    public function __construct(Container $container, ?EnergyRepository $energyRepository = null)
    {
        parent::__construct($container);
        $this->energyRepository = $energyRepository ?? new EnergyRepository($this->container);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        if ($loginResponse = $this->checkAuth($request)) {
            return $loginResponse;
        }

        $user = $this->getUser();

        try {
            $user->getEnergy()->editEnergy(self::ADD_ENERGY);
            $this->energyRepository->save($user->getEnergy());

            return $this->json(['success' => true]);
        } catch (EnergyException $e) {
            return $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
