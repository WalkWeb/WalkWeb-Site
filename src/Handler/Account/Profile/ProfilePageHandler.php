<?php

declare(strict_types=1);

namespace App\Handler\Account\Profile;

use App\Domain\Account\AccountRepository;
use App\Domain\Account\Character\Collection\CharacterCollectionRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class ProfilePageHandler extends AbstractHandler
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

        $this->layoutUrl = 'layout/index.php';
        $repository = new AccountRepository($this->container);
        $characterRepository = new CharacterCollectionRepository($this->container);

        return $this->render('account/profile', [
            'account'       => $repository->get($this->getUser()->getName(), $this->getSendNoticeAction()),
            'characters'    => $characterRepository->get($this->getUser()->getMainCharacterId()),
            'maxCharacters' => MAX_CHARACTERS,
        ]);
    }
}
