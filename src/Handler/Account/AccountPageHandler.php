<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Domain\Account\AccountRepository;
use App\Domain\Account\Character\Collection\CharacterCollectionRepository;
use Exception;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountPageHandler extends AbstractHandler
{
    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        $this->layoutUrl = 'layout/index.php';

        try {
            $repository = new AccountRepository($this->container);
            $characterRepository = new CharacterCollectionRepository($this->container);
            $account = $repository->get($request->getAttribute('name'), $this->getSendNoticeAction());

            return $this->render('account/index', [
                'account'    => $account,
                'characters' => $characterRepository->get($account->getMainCharacter()->getId()),
            ]);

        } catch (Exception $e) {
            // TODO Нужно доработать ошибку во фреймворке и заменить на renderErrorPage()
            return $this->render(
                'errors/custom_404',
                ['error' => 'Пользователь не найден'],
                Response::NOT_FOUND
            );
        }
    }
}
