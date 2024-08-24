<?php

declare(strict_types=1);

namespace App\Handler\Panel;

use App\Domain\Account\Group\AccountGroupInterface;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class PanelPageHandler extends AbstractHandler
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

        $group = $this->getUser()->getGroup()->getId();

        if ($group !== AccountGroupInterface::ADMIN && $group !== AccountGroupInterface::MAIN_ADMIN) {
            return $this->render('errors/custom_403', [], Response::FORBIDDEN);
        }

        return $this->render('panel/index');
    }
}
