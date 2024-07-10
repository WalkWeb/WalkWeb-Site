<?php

declare(strict_types=1);

namespace App\Domain\Account;

use App\Domain\Account\Floor\Floor;
use App\Domain\Account\Group\AccountGroup;
use App\Domain\Account\Group\AccountGroupInterface;
use App\Domain\Account\Status\AccountStatus;
use App\Domain\Account\Status\AccountStatusInterface;
use App\Domain\Account\Upload\AccountUpload;
use DateTime;
use Exception;
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
     * @return AccountInterface
     * @throws AppException
     * @throws AccountException
     */
    public static function createFromDB(array $data): AccountInterface
    {
        $id = self::string($data, 'id', AccountException::INVALID_ID);

        $createdAt = self::string($data, 'created_at', AccountException::INVALID_CREATED_AT);
        $updatedAt = self::string($data, 'updated_at', AccountException::INVALID_UPDATED_AT);

        return new Account(
            self::uuid($id, AccountException::INVALID_ID_VALUE),
            self::loginValidation($data),
            self::nameValidation($data),
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
            self::mainCharacterId($data),
            new Floor(self::int($data, 'floor_id', AccountException::INVALID_FLOOR_ID)),
            new AccountStatus(self::int($data, 'status_id', AccountException::INVALID_STATUS_ID)),
            new AccountGroup(self::int($data, 'group_id', AccountException::INVALID_GROUP_ID)),
            self::uploadValidate($data),
            self::date($createdAt, AccountException::INVALID_CREATED_AT_VALUE),
            self::date($updatedAt, AccountException::INVALID_CREATED_AT_VALUE),
        );
    }

    /**
     * Create object Account from data register page
     *
     * @param array $data
     * @param string $hashKey
     * @return AccountInterface
     * @throws AppException
     */
    public static function createNew(array $data, string $hashKey): AccountInterface
    {
        try {
            $login = self::loginValidation($data);
            $password = self::passwordValidate($data);
            $password = password_hash($password . $hashKey, PASSWORD_BCRYPT, ['cost' => 10]);

            return new Account(
                Uuid::uuid4()->toString(),
                $login,
                $login,
                $password,
                self::emailValidate($data),
                false,
                false,
                self::generateString(30),
                self::generateString(30),
                TEMPLATE_DEFAULT,
                self::ipValidate($data),
                self::refValidate($data),
                self::userAgentValidate($data),
                true,
                '',
                new Floor(self::int($data, 'floor_id', AccountException::INVALID_FLOOR_ID)),
                new AccountStatus(AccountStatusInterface::ACTIVE),
                new AccountGroup(AccountGroupInterface::USER),
                new AccountUpload(0, AccountInterface::UPLOAD_MAX_BASE),
                new DateTime(),
                new DateTime(),
            );
        } catch (Exception $e) {
            throw new AppException($e->getMessage());
        }
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
     * @return AccountUpload
     * @throws AppException
     */
    private static function uploadValidate(array $data): AccountUpload
    {
        $upload = self::int($data, 'upload', AccountException::INVALID_UPLOAD);

        self::intMinMaxValue(
            $upload,
            AccountInterface::UPLOAD_MIN_VALUE,
            AccountInterface::UPLOAD_MAX_VALUE,
            AccountException::INVALID_UPLOAD_VALUE . AccountInterface::UPLOAD_MIN_VALUE . '-' . AccountInterface::UPLOAD_MAX_VALUE
        );

        return new AccountUpload($upload, AccountInterface::UPLOAD_MAX_BASE);
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function mainCharacterId(array $data): string
    {
        $mainCharacterId = self::string($data, 'main_character_id', AccountException::INVALID_MAIN_CHAR_ID);

        self::stringMinMaxLength(
            $mainCharacterId,
            AccountInterface::MAIN_CHARACTER_MIN_LENGTH,
            AccountInterface::MAIN_CHARACTER_MAX_LENGTH,
            AccountException::INVALID_MAIN_CHAR_ID_LENGTH . AccountInterface::MAIN_CHARACTER_MIN_LENGTH . '-' . AccountInterface::MAIN_CHARACTER_MAX_LENGTH
        );

        return $mainCharacterId;
    }
}
