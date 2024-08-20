<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Domain\Account\AccountException;
use App\Domain\Account\AccountFactory;
use App\Domain\Account\AccountInterface;
use App\Domain\Account\AccountRepository;
use App\Domain\Account\Carma\CarmaFactory;
use App\Domain\Account\Carma\CarmaRepository;
use App\Domain\Account\Character\Avatar\AvatarInterface;
use App\Domain\Account\Character\Avatar\AvatarRepository;
use App\Domain\Account\Character\CharacterException;
use App\Domain\Account\Character\CharacterFactory;
use App\Domain\Account\Character\CharacterInterface;
use App\Domain\Account\Character\CharacterRepository;
use App\Domain\Account\Character\Genesis\GenesisInterface;
use App\Domain\Account\Character\Genesis\GenesisRepository;
use App\Domain\Account\Character\Profession\ProfessionInterface;
use App\Domain\Account\Character\Profession\ProfessionRepository;
use App\Domain\Account\DTO\CreateAccountRequest;
use App\Domain\Account\MainCharacter\MainCharacterFactory;
use App\Domain\Account\MainCharacter\MainCharacterInterface;
use App\Domain\Account\MainCharacter\MainCharacterRepository;
use App\Domain\Account\Notice\Action\SendNoticeAction;
use App\Domain\Account\Notice\Action\SendNoticeActionInterface;
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
    private CharacterRepository $characterRepository;
    private GenesisRepository $genesisRepository;
    private ProfessionRepository $professionRepository;
    private AvatarRepository $avatarRepository;
    private CarmaRepository $carmaRepository;

    public function __construct(
        Container $container,
        SendNoticeAction $sendNoticeAction = null,
        AccountRepository $accountRepository = null,
        MainCharacterRepository $mainCharacterRepository = null,
        CharacterRepository $characterRepository = null,
        GenesisRepository $genesisRepository = null,
        ProfessionRepository $professionRepository = null,
        AvatarRepository $avatarRepository = null,
        CarmaRepository $carmaRepository = null
    )
    {
        parent::__construct($container);
        $this->sendNoticeAction = $sendNoticeAction ?? new SendNoticeAction(new NoticeRepository($this->container));
        $this->accountRepository = $accountRepository ?? new AccountRepository($this->container);
        $this->mainCharacterRepository = $mainCharacterRepository ?? new MainCharacterRepository($this->container);
        $this->characterRepository = $characterRepository ?? new CharacterRepository($this->container);
        $this->genesisRepository = $genesisRepository ?? new GenesisRepository($this->container);
        $this->professionRepository = $professionRepository ?? new ProfessionRepository($this->container);
        $this->avatarRepository = $avatarRepository ?? new AvatarRepository($this->container);
        $this->carmaRepository = $carmaRepository ?? new CarmaRepository($this->container);
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
        if ($this->container->exist('user')) {
            return $this->redirect('/');
        }

        // TODO Добавить откат транзакции, если что-то пошло не так

        try {
            $csrfToken = $request->csrf;

            if (!$this->container->getCsrf()->checkCsrfToken($csrfToken ?? '')) {
                throw new AppException('Invalid csrf-token');
            }

            if (mb_strlen($request->ref) > AccountInterface::REF_MAX_LENGTH) {
                throw new AppException(AccountException::INVALID_REF_LENGTH . AccountInterface::REF_MIN_LENGTH . '-' . AccountInterface::REF_MAX_LENGTH);
            }

            $requestDto = $this->createRequest($request);
            $genesis = $this->getGenesis($requestDto);
            $profession = $this->getProfession($requestDto, $genesis);
            $avatar = $this->getAvatar($requestDto, $genesis);

            $account = $this->createAccount($requestDto, $avatar);
            $mainCharacter = $this->createMainCharacter($account);
            $character = $this->createCharacter($requestDto, $account, $mainCharacter, $genesis, $profession, $avatar);
            $this->accountRepository->setMainCharacterId($account, $mainCharacter);
            $this->accountRepository->setCharacterId($account, $character);
            $this->carmaRepository->add(CarmaFactory::createNew($account->getId()));
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

        return AccountFactory::createRequest($body);
    }

    /**
     * @param CreateAccountRequest $request
     * @param AvatarInterface $avatar
     * @return AccountInterface
     * @throws AppException
     */
    private function createAccount(CreateAccountRequest $request, AvatarInterface $avatar): AccountInterface
    {
        $account = AccountFactory::createNew($request, $avatar, KEY);
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
     * @param CreateAccountRequest $request
     * @param AccountInterface $account
     * @param MainCharacterInterface $mainCharacter
     * @param GenesisInterface $genesis
     * @param ProfessionInterface $profession
     * @param AvatarInterface $avatar
     * @return CharacterInterface
     * @throws AppException
     */
    private function createCharacter(
        CreateAccountRequest $request,
        AccountInterface $account,
        MainCharacterInterface $mainCharacter,
        GenesisInterface $genesis,
        ProfessionInterface $profession,
        AvatarInterface $avatar
    ): CharacterInterface
    {
        $character = CharacterFactory::createNew($request, $account, $mainCharacter, $genesis, $profession, $avatar);
        $this->characterRepository->add($character, $mainCharacter);
        return $character;
    }

    /**
     * @param CreateAccountRequest $request
     * @return GenesisInterface
     * @throws AppException
     */
    private function getGenesis(CreateAccountRequest $request): GenesisInterface
    {
        $genesis = $this->genesisRepository->get($request->getGenesis(), THEME);

        if (!$genesis) {
            throw new AppException(CharacterException::UNKNOWN_GENESIS_ID);
        }

        return $genesis;
    }

    /**
     * @param CreateAccountRequest $request
     * @param GenesisInterface $genesis
     * @return ProfessionInterface
     * @throws AppException
     */
    private function getProfession(CreateAccountRequest $request, GenesisInterface $genesis): ProfessionInterface
    {
        $profession = $this->professionRepository->get($request->getProfession(), $genesis->getId());

        if (!$profession) {
            throw new AppException(CharacterException::UNKNOWN_PROFESSION_ID);
        }

        return $profession;
    }

    /**
     * @param CreateAccountRequest $request
     * @param GenesisInterface $genesis
     * @return AvatarInterface
     * @throws AppException
     */
    private function getAvatar(CreateAccountRequest $request, GenesisInterface $genesis): AvatarInterface
    {
        $avatar = $this->avatarRepository->getForRegister($request->getAvatar(), $genesis->getId(), $request->getFloor());

        if (!$avatar) {
            throw new AppException(CharacterException::UNKNOWN_AVATAR_ID);
        }

        return $avatar;
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
     * @throws AppException
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
