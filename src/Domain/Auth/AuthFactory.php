<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\Account\AccountInterface;
use App\Domain\Account\Energy\EnergyFactory;
use App\Domain\Account\Group\AccountGroup;
use App\Domain\Account\MainCharacter\Level\LevelFactory;
use App\Domain\Account\MainCharacter\Level\LevelInterface;
use App\Domain\Account\Notice\Action\SendNoticeActionInterface;
use App\Domain\Account\Notice\NoticeCollectionFactory;
use App\Domain\Account\Status\AccountStatus;
use App\Domain\Account\Upload\AccountUpload;
use Exception;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class AuthFactory
{
    use ValidationTrait;

    /**
     * Создает объект реализующий интерфейс AuthInterface на основе массива с данными
     *
     * @param array $data
     * @param SendNoticeActionInterface $sendNoticeAction
     * @return AuthInterface
     * @throws AppException
     */
    public static function create(array $data, SendNoticeActionInterface $sendNoticeAction): AuthInterface
    {
        try {
            $level = LevelFactory::create(self::array($data, 'level', AuthException::INVALID_LEVEL), $sendNoticeAction);
            $uploadBonus = self::int($data, 'upload_bonus', AuthException::INVALID_UPLOAD_BONUS);

            return new Auth(
                self::string($data, 'id', AuthException::INVALID_ID),
                self::string($data, 'name', AuthException::INVALID_NAME),
                self::string($data, 'avatar', AuthException::INVALID_AVATAR),
                self::verifiedTokenValidate($data),
                new AccountGroup(self::int($data, 'account_group_id', AuthException::INVALID_ACCOUNT_GROUP_ID)),
                new AccountStatus(self::int($data, 'account_status_id', AuthException::INVALID_ACCOUNT_STATUS_ID)),
                EnergyFactory::create(self::array($data, 'energy', AuthException::INVALID_ENERGY_DATA)),
                (bool)self::int($data, 'can_like', AuthException::INVALID_CAN_LIKE),
                NoticeCollectionFactory::create(self::array($data, 'notices', AuthException::INVALID_NOTICES_DATA)),
                $level,
                self::string($data, 'template', AuthException::INVALID_TEMPLATE),
                (bool)self::int($data, 'email_verified', AuthException::INVALID_EMAIL_VERIFIED),
                self::uploadValidate($data, $level, $uploadBonus)
            );
        } catch (Exception $e) {
            throw new AppException($e->getMessage());
        }
    }

    /**
     * @param array $data
     * @param LevelInterface $level
     * @param int $uploadBonus
     * @return AccountUpload
     * @throws AppException
     */
    private static function uploadValidate(array $data, LevelInterface $level, int $uploadBonus): AccountUpload
    {
        $upload = self::int($data, 'upload', AuthException::INVALID_UPLOAD);

        self::intMinMaxValue(
            $upload,
            AccountInterface::UPLOAD_MIN_VALUE,
            AccountInterface::UPLOAD_MAX_VALUE,
            AuthException::INVALID_UPLOAD_VALUE . AccountInterface::UPLOAD_MIN_VALUE . '-' . AccountInterface::UPLOAD_MAX_VALUE
        );

        $uploadMax =
            AccountInterface::UPLOAD_MAX_BASE +
            ($level->getLevel() - 1) * AccountInterface::UPLOAD_PER_LEVEL +
            $uploadBonus * AccountInterface::UPLOAD_PER_STAT;

        return new AccountUpload($upload, $uploadMax);
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function verifiedTokenValidate(array $data): string
    {
        $verifiedToken = self::string($data, 'verified_token', AuthException::INVALID_VERIFIED_TOKEN);

        self::stringMinMaxLength(
            $verifiedToken,
            AccountInterface::AUTH_TOKEN_MIN_LENGTH,
            AccountInterface::AUTH_TOKEN_MAX_LENGTH,
            AuthException::INVALID_VERIFIED_TOKEN_LENGTH . AccountInterface::AUTH_TOKEN_MIN_LENGTH . '-' . AccountInterface::AUTH_TOKEN_MAX_LENGTH
        );

        return $verifiedToken;
    }
}
