<?php

declare(strict_types=1);

namespace App\Domain\Account;

use App\Domain\Account\Carma\Carma;
use App\Domain\Account\Carma\CarmaFactory;
use App\Domain\Account\Character\Avatar\AvatarInterface;
use App\Domain\Account\Character\Season\Season;
use App\Domain\Account\DTO\CreateAccountRequest;
use App\Domain\Account\Floor\Floor;
use App\Domain\Account\Group\AccountGroup;
use App\Domain\Account\Group\AccountGroupInterface;
use App\Domain\Account\MainCharacter\MainCharacterFactory;
use App\Domain\Account\MainCharacter\MainCharacterInterface;
use App\Domain\Account\Notice\Action\SendNoticeActionInterface;
use App\Domain\Account\Status\AccountStatus;
use App\Domain\Account\Status\AccountStatusInterface;
use App\Domain\Account\Upload\AccountUpload;
use App\Domain\Account\Upload\UploadInterface;
use DateTime;
use Ramsey\Uuid\Uuid;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\StringTrait;
use WalkWeb\NW\Traits\ValidationTrait;

class AccountFactory
{
    use ValidationTrait;
    use StringTrait;

    /**
     * Create object Account from array (data from database)
     *
     * @param array $data
     * @param SendNoticeActionInterface $sendNoticeAction
     * @return AccountInterface
     * @throws AppException
     */
    public static function create(array $data, SendNoticeActionInterface $sendNoticeAction): AccountInterface
    {
        if (array_key_exists('main_character', $data)) {
            $mainCharacter = MainCharacterFactory::create(
                self::array($data, 'main_character', AccountException::INVALID_MAIN_CHARACTER),
                $sendNoticeAction
            );
        } else {
            $mainCharacter = null;
        }

        return new Account(
            self::uuid($data, 'id', AccountException::INVALID_ID),
            self::loginValidation($data),
            self::nameValidation($data),
            self::avatarValidation($data),
            self::passwordValidate($data),
            self::emailValidate($data),
            (bool)self::int($data, 'email_verified', AccountException::INVALID_EMAIL_VERIFIED),
            (bool)self::int($data, 'reg_complete', AccountException::INVALID_REG_COMPLETE),
            self::authTokenValidate($data),
            self::verifiedTokenValidate($data),
            self::templateValidate($data),
            self::ipValidate($data),
            self::refValidate($data),
            self::userAgentValidate($data),
            (bool)self::int($data, 'can_like', AccountException::INVALID_CAN_LIKE),
            self::int($data, 'post_count', AccountException::INVALID_POST_COUNT),
            self::int($data, 'comment_count', AccountException::INVALID_COMMENT_COUNT),
            new Floor(self::int($data, 'floor_id', AccountException::INVALID_FLOOR_ID)),
            new AccountStatus(self::int($data, 'status_id', AccountException::INVALID_STATUS_ID)),
            new AccountGroup(self::int($data, 'group_id', AccountException::INVALID_GROUP_ID)),
            self::uploadValidate($data, $mainCharacter),
            CarmaFactory::create($data),
            self::date($data, 'created_at', AccountException::INVALID_CREATED_AT),
            self::date($data, 'updated_at', AccountException::INVALID_UPDATED_AT),
            $mainCharacter,
        );
    }

    /**
     * Create object Account on register page
     *
     * @param CreateAccountRequest $request
     * @param AvatarInterface $avatar
     * @param string $hashKey
     * @return AccountInterface
     * @throws AppException
     */
    public static function createNew(CreateAccountRequest $request, AvatarInterface $avatar, string $hashKey): AccountInterface
    {
        // TODO Если user_agent больше допустимой длины - то просто обрезать его, без ошибки

        $id = Uuid::uuid4()->toString();

        return new Account(
            $id,
            $request->getLogin(),
            $request->getLogin(),
            $avatar->getOriginUrl(),
            password_hash($request->getPassword(). $hashKey, PASSWORD_BCRYPT, ['cost' => 10]),
            $request->getEmail(),
            false,
            false,
            self::generateString(30),
            self::generateString(30),
            TEMPLATE_DEFAULT,
            $request->getIp(),
            $request->getReferral(),
            $request->getUserAgent(),
            true,
            0,
            0,
            new Floor($request->getFloor()),
            new AccountStatus(AccountStatusInterface::ACTIVE),
            new AccountGroup(AccountGroupInterface::USER),
            new AccountUpload(0, UploadInterface::UPLOAD_MAX_BASE),
            new Carma(Uuid::uuid4()->toString(), $id, new Season(ACTIVE_SEASON), 0, 0),
            new DateTime(),
            new DateTime(),
            null,
        );
    }

    /**
     * @param array $data
     * @return CreateAccountRequest
     * @throws AppException
     */
    public static function createRequest(array $data): CreateAccountRequest
    {
        return new CreateAccountRequest(
            self::loginValidation($data),
            self::emailValidate($data),
            self::passwordValidate($data),
            (int)self::string($data, 'floor_id', AccountException::INVALID_REQUEST_FLOOR_ID),
            (int)self::string($data, 'genesis_id', AccountException::INVALID_GENESIS_ID),
            (int)self::string($data, 'profession_id', AccountException::INVALID_PROFESSION_ID),
            (int)self::string($data, 'avatar_id', AccountException::INVALID_AVATAR_ID),
            self::refValidate($data),
            self::userAgentValidate($data),
            self::ipValidate($data),
        );
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function loginValidation(array $data): string
    {
        $login = self::string($data, 'login', AccountException::INVALID_LOGIN);

        self::stringMinMaxLength(
            $login,
            AccountInterface::LOGIN_MIN_LENGTH,
            AccountInterface::LOGIN_MAX_LENGTH,
            AccountException::INVALID_LOGIN_LENGTH . AccountInterface::LOGIN_MIN_LENGTH . '-' . AccountInterface::LOGIN_MAX_LENGTH
        );

        self::parent($login, AccountInterface::LOGIN_PARENT, AccountException::INVALID_LOGIN_SYMBOL);

        return $login;
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function nameValidation(array $data): string
    {
        $name = self::string($data, 'name', AccountException::INVALID_NAME);

        self::stringMinMaxLength(
            $name,
            AccountInterface::NAME_MIN_LENGTH,
            AccountInterface::NAME_MAX_LENGTH,
            AccountException::INVALID_NAME_LENGTH . AccountInterface::NAME_MIN_LENGTH . '-' . AccountInterface::NAME_MAX_LENGTH
        );

        self::parent($name, AccountInterface::NAME_PARENT, AccountException::INVALID_NAME_SYMBOL);

        return $name;
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function avatarValidation(array $data): string
    {
        $avatar = self::string($data, 'avatar', AccountException::INVALID_AVATAR);

        self::stringMinMaxLength(
            $avatar,
            AccountInterface::AVATAR_MIN_LENGTH,
            AccountInterface::AVATAR_MAX_LENGTH,
            AccountException::INVALID_AVATAR_LENGTH . AccountInterface::AVATAR_MIN_LENGTH . '-' . AccountInterface::AVATAR_MAX_LENGTH
        );

        return $avatar;
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function passwordValidate(array $data): string
    {
        $password = self::string($data, 'password', AccountException::INVALID_PASSWORD);

        self::stringMinMaxLength(
            $password,
            AccountInterface::PASSWORD_MIN_LENGTH,
            AccountInterface::PASSWORD_MAX_LENGTH,
            AccountException::INVALID_PASSWORD_LENGTH . AccountInterface::PASSWORD_MIN_LENGTH . '-' . AccountInterface::PASSWORD_MAX_LENGTH
        );

        return $password;
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function emailValidate(array $data): string
    {
        $email = self::string($data, 'email', AccountException::INVALID_EMAIL);

        self::stringMinMaxLength(
            $email,
            AccountInterface::EMAIL_MIN_LENGTH,
            AccountInterface::EMAIL_MAX_LENGTH,
            AccountException::INVALID_EMAIL_LENGTH . AccountInterface::EMAIL_MIN_LENGTH . '-' . AccountInterface::EMAIL_MAX_LENGTH
        );

        self::email($email, AccountException::INVALID_EMAIL_SYMBOL);

        return $email;
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function authTokenValidate(array $data): string
    {
        $authToken = self::string($data, 'auth_token', AccountException::INVALID_AUTH_TOKEN);

        self::stringMinMaxLength(
            $authToken,
            AccountInterface::AUTH_TOKEN_MIN_LENGTH,
            AccountInterface::AUTH_TOKEN_MAX_LENGTH,
            AccountException::INVALID_AUTH_TOKEN_LENGTH . AccountInterface::AUTH_TOKEN_MIN_LENGTH . '-' . AccountInterface::AUTH_TOKEN_MAX_LENGTH
        );

        return $authToken;
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function verifiedTokenValidate(array $data): string
    {
        $verifiedToken = self::string($data, 'verified_token', AccountException::INVALID_VERIFIED_TOKEN);

        self::stringMinMaxLength(
            $verifiedToken,
            AccountInterface::AUTH_TOKEN_MIN_LENGTH,
            AccountInterface::AUTH_TOKEN_MAX_LENGTH,
            AccountException::INVALID_VERIFIED_TOKEN_LENGTH . AccountInterface::AUTH_TOKEN_MIN_LENGTH . '-' . AccountInterface::AUTH_TOKEN_MAX_LENGTH
        );

        return $verifiedToken;
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function templateValidate(array $data): string
    {
        $template = self::string($data, 'template', AccountException::INVALID_TEMPLATE);

        self::stringMinMaxLength(
            $template,
            AccountInterface::TEMPLATE_MIN_LENGTH,
            AccountInterface::TEMPLATE_MAX_LENGTH,
            AccountException::INVALID_TEMPLATE_LENGTH . AccountInterface::TEMPLATE_MIN_LENGTH . '-' . AccountInterface::TEMPLATE_MAX_LENGTH
        );

        return $template;
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function ipValidate(array $data): string
    {
        $ip = self::string($data, 'ip', AccountException::INVALID_IP);

        self::stringMinMaxLength(
            $ip,
            AccountInterface::IP_MIN_LENGTH,
            AccountInterface::IP_MAX_LENGTH,
            AccountException::INVALID_IP_LENGTH . AccountInterface::IP_MIN_LENGTH . '-' . AccountInterface::IP_MAX_LENGTH
        );

        return $ip;
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function refValidate(array $data): string
    {
        $ref = self::string($data, 'ref', AccountException::INVALID_REF);

        self::stringMinMaxLength(
            $ref,
            AccountInterface::REF_MIN_LENGTH,
            AccountInterface::REF_MAX_LENGTH,
            AccountException::INVALID_REF_LENGTH . AccountInterface::REF_MIN_LENGTH . '-' . AccountInterface::REF_MAX_LENGTH
        );

        return $ref;
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function userAgentValidate(array $data): string
    {
        $userAgent = self::string($data, 'user_agent', AccountException::INVALID_USER_AGENT);

        self::stringMinMaxLength(
            $userAgent,
            AccountInterface::USER_AGENT_MIN_LENGTH,
            AccountInterface::USER_AGENT_MAX_LENGTH,
            AccountException::INVALID_USER_AGENT_LENGTH . AccountInterface::USER_AGENT_MIN_LENGTH . '-' . AccountInterface::USER_AGENT_MAX_LENGTH
        );

        return $userAgent;
    }

    /**
     * @param array $data
     * @param MainCharacterInterface|null $mainCharacter
     * @return AccountUpload
     * @throws AppException
     */
    private static function uploadValidate(array $data, ?MainCharacterInterface $mainCharacter = null): AccountUpload
    {
        $upload = self::int($data, 'upload', AccountException::INVALID_UPLOAD);

        self::intMinMaxValue(
            $upload,
            UploadInterface::UPLOAD_MIN_VALUE,
            UploadInterface::UPLOAD_MAX_VALUE,
            AccountException::INVALID_UPLOAD_VALUE . UploadInterface::UPLOAD_MIN_VALUE . '-' . UploadInterface::UPLOAD_MAX_VALUE
        );

        $uploadMax = $mainCharacter === null ? UploadInterface::UPLOAD_MAX_BASE :
            UploadInterface::UPLOAD_MAX_BASE +
            ($mainCharacter->getLevel()->getLevel() - 1) * UploadInterface::UPLOAD_PER_LEVEL +
            $mainCharacter->getUploadBonus() * UploadInterface::UPLOAD_PER_STAT;

        return new AccountUpload($upload, $uploadMax);
    }
}
