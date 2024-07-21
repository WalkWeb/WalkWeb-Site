<?php

declare(strict_types=1);

namespace App\Handler\Temporary;

use App\Domain\Account\Energy\EnergyException;
use App\Domain\Account\Energy\EnergyRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class ReducedEnergyHandler extends AbstractHandler
{
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
            $user->getEnergy()->editEnergy(-40);
            $repository = new EnergyRepository($this->container);
            $repository->save($user->getEnergy());

            return $this->json(['success' => true]);
        } catch (EnergyException $e) {
            return $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
