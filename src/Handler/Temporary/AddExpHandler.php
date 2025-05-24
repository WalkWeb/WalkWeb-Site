<?php

declare(strict_types=1);

namespace App\Handler\Temporary;

use App\Domain\Account\MainCharacter\Level\LevelException;
use App\Domain\Account\MainCharacter\MainCharacterRepository;
use App\Domain\Account\Notice\NoticeException;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AddExpHandler extends AbstractHandler
{
    public const ADD_EXP = 60;

    private MainCharacterRepository $repository;

    public function __construct(Container $container, ?MainCharacterRepository $repository = null)
    {
        parent::__construct($container);
        $this->repository = $repository ?? new MainCharacterRepository($this->container);
    }

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

        $character = $this->repository->get($user->getMainCharacterId(), $this->getSendNoticeAction());
        $character->getLevel()->addExp(self::ADD_EXP);

        $this->repository->save($character->getId(), $character->getLevel());

        return $this->redirect('/profile');
    }
}
