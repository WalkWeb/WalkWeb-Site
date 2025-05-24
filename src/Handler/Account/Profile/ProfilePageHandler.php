<?php

declare(strict_types=1);

namespace App\Handler\Account\Profile;

use App\Domain\Account\AccountRepository;
use App\Domain\Account\Character\Collection\CharacterCollectionRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class ProfilePageHandler extends AbstractHandler
{
    private AccountRepository $accountRepository;
    private CharacterCollectionRepository $characterRepository;

    public function __construct(
        Container $container,
        ?AccountRepository $accountRepository = null,
        ?CharacterCollectionRepository $characterRepository = null
    ) {
        parent::__construct($container);
        $this->accountRepository = $accountRepository ?? new AccountRepository($this->container);
        $this->characterRepository = $characterRepository ?? new CharacterCollectionRepository($this->container);
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

        $this->layoutUrl = 'layout/index.php';

        return $this->render('account/profile', [
            'account'       => $this->accountRepository->get($this->getUser()->getName(), $this->getSendNoticeAction()),
            'characters'    => $this->characterRepository->get($this->getUser()->getMainCharacterId()),
            'maxCharacters' => MAX_CHARACTERS,
        ]);
    }
}
