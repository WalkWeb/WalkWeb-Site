<?php

declare(strict_types=1);

namespace App\Handler\Character;

use App\Domain\Account\AccountException;
use App\Domain\Account\Character\CharacterRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class CharacterPageHandler extends AbstractHandler
{
    /**
     * @param Request $request
     * @return Response
     * @throws AccountException
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        $repository = new CharacterRepository($this->container);

        if ($character = $repository->get($request->id)) {
            return $this->render('character/index', ['character' => $character]);
        }

        // TODO Нужно доработать ошибку во фреймворке и заменить на renderErrorPage()
        return $this->render(
            'errors/custom_404',
            ['error' => 'Персонаж не найден'],
            Response::NOT_FOUND
        );
    }
}
