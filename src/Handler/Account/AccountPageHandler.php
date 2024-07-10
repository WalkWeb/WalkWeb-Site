<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Domain\Account\AccountRepository;
use App\Domain\Account\MainCharacter\MainCharacterRepository;
use App\Domain\Account\Notice\Action\SendNoticeAction;
use App\Domain\Account\Notice\NoticeRepository;
use Exception;
use WalkWeb\NW\AbstractHandler;
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
        try {
            $repository = new AccountRepository($this->container);
            $mainCharacterRepository = new MainCharacterRepository($this->container);
            $noticeSenderAction = new SendNoticeAction(new NoticeRepository($this->container));

            return $this->render('account/index', [
                'account'       => $account = $repository->get($request->getAttribute('name')),
                'mainCharacter' => $mainCharacterRepository->get($account->getMainCharacterId(), $noticeSenderAction),
            ]);

        } catch (Exception $e) {

            // TODO Нужно доработать ошибку во фреймворке и заменить на renderErrorPage()
            return $this->render('errors/custom_404', ['error' => 'Пользователь не найден'], Response::NOT_FOUND);
        }
    }
}
