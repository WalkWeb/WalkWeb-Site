<?php

declare(strict_types=1);

namespace App\Handler;

use App\Domain\Statistic\Statistic;
use App\Domain\Statistic\StatisticRepository;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class StatisticHandler extends AbstractHandler
{
    /**
     * Print statistic page
     *
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        return $this->render('statistic/index', [
            'statistic' => new Statistic(new StatisticRepository($this->getContainer())),
        ]);
    }
}
