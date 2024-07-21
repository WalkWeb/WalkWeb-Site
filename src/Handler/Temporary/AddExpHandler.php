<?php

declare(strict_types=1);

namespace App\Handler\Temporary;

use App\Domain\Account\MainCharacter\Level\LevelException;
use App\Domain\Account\MainCharacter\MainCharacterRepository;
use App\Domain\Account\Notice\NoticeException;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AddExpHandler extends AbstractHandler
{
    public const ADD_EXP = 60;

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     * @throws LevelException
     * @throws NoticeException
     */
    public function __invoke(Request $request): Response
    {
        if ($loginResponse = $this->checkAuth($request)) {
            return $loginResponse;
        }

        $user = $this->getUser();

        $repository = new MainCharacterRepository($this->container);
        $character = $repository->get($user->getMainCharacterId(), $this->getSendNoticeAction());
        $character->getLevel()->addExp(self::ADD_EXP);

        $repository->update($character);

        return $this->redirect('/profile');
    }
}
