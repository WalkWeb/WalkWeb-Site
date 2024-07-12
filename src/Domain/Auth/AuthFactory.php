<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\Account\Energy\EnergyFactory;
use App\Domain\Account\Group\AccountGroup;
use App\Domain\Account\MainCharacter\Level\LevelFactory;
use App\Domain\Account\MainCharacter\Level\LevelInterface;
use App\Domain\Account\Notice\Action\SendNoticeActionInterface;
use App\Domain\Account\Notice\NoticeCollectionFactory;
use App\Domain\Account\Status\AccountStatus;
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
            return new Auth(
                self::string($data, 'id', AuthException::INVALID_ID),
                self::string($data, 'name', AuthException::INVALID_NAME),
                self::string($data, 'avatar', AuthException::INVALID_AVATAR),
                new AccountGroup(self::int($data, 'account_group_id', AuthException::INVALID_ACCOUNT_GROUP_ID)),
                new AccountStatus(self::int($data, 'account_status_id', AuthException::INVALID_ACCOUNT_STATUS_ID)),
                EnergyFactory::createFromDB(self::array($data, 'energy', AuthException::INVALID_ENERGY_DATA)),
                (bool)self::int($data, 'can_like', AuthException::INVALID_CAN_LIKE),
                NoticeCollectionFactory::create(self::array($data, 'notices', AuthException::INVALID_NOTICES_DATA)),
                LevelFactory::create(self::array($data, 'level', AuthException::INVALID_LEVEL), $sendNoticeAction),
                self::int($data, 'stat_points', AuthException::INVALID_STAT_POINTS),
                self::string($data, 'template', AuthException::INVALID_TEMPLATE),
                (bool)self::int($data, 'email_verified', AuthException::INVALID_EMAIL_VERIFIED)
            );
        } catch (Exception $e) {
            throw new AppException($e->getMessage());
        }
    }
}
