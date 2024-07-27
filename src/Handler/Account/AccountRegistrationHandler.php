<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Domain\Account\AccountException;
use App\Domain\Account\AccountFactory;
use App\Domain\Account\AccountInterface;
use App\Domain\Account\AccountRepository;
use App\Domain\Account\DTO\CreateAccountRequest;
use App\Domain\Account\MainCharacter\MainCharacterFactory;
use App\Domain\Account\MainCharacter\MainCharacterInterface;
use App\Domain\Account\MainCharacter\MainCharacterRepository;
use App\Domain\Account\Notice\Action\SendNoticeAction;
use App\Domain\Account\Notice\Action\SendNoticeActionInterface;
use App\Domain\Account\Notice\NoticeException;
use App\Domain\Account\Notice\NoticeInterface;
use App\Domain\Account\Notice\NoticeRepository;
use Exception;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountRegistrationHandler extends AbstractHandler
{
    private SendNoticeActionInterface $sendNoticeAction;
    private AccountRepository $accountRepository;
    private MainCharacterRepository $mainCharacterRepository;

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->sendNoticeAction = new SendNoticeAction(new NoticeRepository($this->container));
        $this->accountRepository = new AccountRepository($this->container);
        $this->mainCharacterRepository = new MainCharacterRepository($this->container);
    }

    /**
     * Создание нового пользователя на основе данных из формы регистрации
     *
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        // TODO Проверка на уже существующую авторизацию

        try {
            $csrfToken = $request->csrf;

            if (!$this->container->getCsrf()->checkCsrfToken($csrfToken ?? '')) {
                throw new AppException('Invalid csrf-token');
            }

            if (mb_strlen($request->ref) > AccountInterface::REF_MAX_LENGTH) {
                throw new AppException(AccountException::INVALID_REF_LENGTH . AccountInterface::REF_MIN_LENGTH . '-' . AccountInterface::REF_MAX_LENGTH);
            }

            $requestDto = $this->createRequest($request);
            $account = $this->createAccount($requestDto);
            $mainCharacter = $this->createMainCharacter($account);
            $this->accountRepository->setMainCharacterId($account, $mainCharacter);
            $this->container->getCookies()->set(AccountInterface::AUTH_TOKEN, $account->getAuthToken());
            $this->sendMail($account);
            $this->sendNotice($account);

            return $this->redirect('/verified/email');

        } catch (Exception $e) {
            $body = $request->getBody();
            return $this->render(
                'account/registration',
                [
                    'error'     => $e->getMessage(),
                    'csrfToken' => $this->container->getCsrf()->getCsrfToken(),
                    'login'     => $body['login'] ?? '',
                    'email'     => $body['email'] ?? '',
                    'floor'     => $body['floor_id'] ?? 1,
                    'ref'       => $request->ref,
                ]
            );
        }
    }

    /**
     * @param Request $request
     * @return CreateAccountRequest
     * @throws AppException
     */
    private function createRequest(Request $request): CreateAccountRequest
    {
        $body = $request->getBody();

        $body['ip'] = $this->getIp($request);
        $body['ref'] = $request->ref;
        $body['user_agent'] = ''; // TODO Mock

        return AccountFactory::createRequest($body);
    }

    /**
     * @param CreateAccountRequest $request
     * @return AccountInterface
     * @throws AppException
     */
    private function createAccount(CreateAccountRequest $request): AccountInterface
    {
        $account = AccountFactory::createNew($request, KEY);
        $this->accountRepository->add($account);
        return $account;
    }

    /**
     * @param AccountInterface $account
     * @return MainCharacterInterface
     * @throws AppException
     */
    private function createMainCharacter(AccountInterface $account): MainCharacterInterface
    {
        $mainCharacter = MainCharacterFactory::createNew($account->getId(), $this->sendNoticeAction);
        $this->mainCharacterRepository->add($mainCharacter);
        return $mainCharacter;
    }

    /**
     * @param AccountInterface $account
     * @throws AppException
     */
    private function sendMail(AccountInterface $account): void
    {
        $url = HOST . 'check/email/' . $account->getVerifiedToken();
        $appName = APP_NAME;

        $this->container->getMailer()->send(
            $account->getEmail(),
            "Подтверждение регистрации на $appName",
            "<p>Кто-то (возможно, вы) зарегистрировался на $appName, если это были вы - для завершения регистрации перейдите 
                        по ссылке <a href='$url'>$url</a></p>
                        <p>Если вы не регистрировались на $appName, то просто проигнорируйте это письмо.</p>
                        <p>В любом случае не передавайте третьим лицам ссылку из письма.</p>",
        );
    }

    /**
     * @param AccountInterface $account
     * @throws NoticeException
     */
    private function sendNotice(AccountInterface $account): void
    {
        $this->sendNoticeAction->send(
            $account->getId(),
            NoticeInterface::REGISTER_START,
            NoticeInterface::TYPE_INFO,
            false
        );
    }

    /**
     * @param Request $request
     * @return string
     */
    private function getIp(Request $request): string
    {
        if (array_key_exists('HTTP_CLIENT_IP', $request->getServer())) {
            return (string)$request->getServer()['HTTP_CLIENT_IP'];
        }

        if (array_key_exists('HTTP_X_FORWARDED_FOR', $request->getServer())) {
            return (string)$request->getServer()['HTTP_X_FORWARDED_FOR'];
        }

        if (array_key_exists('REMOTE_ADDR', $request->getServer())) {
            return (string)$request->getServer()['REMOTE_ADDR'];
        }

        return 'undefined';
    }
}
