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
            self::string($data, 'id', AuthException::INVALID_ID);
            self::string($data, 'name', AuthException::INVALID_NAME);
            self::string($data, 'avatar', AuthException::INVALID_AVATAR);
            self::int($data, 'account_group_id', AuthException::INVALID_ACCOUNT_GROUP_ID);
            self::int($data, 'account_status_id', AuthException::INVALID_ACCOUNT_STATUS_ID);
            self::array($data, 'energy', AuthException::INVALID_ENERGY_DATA);
            self::int($data, 'can_like', AuthException::INVALID_CAN_LIKE);
            self::array($data, 'notices', AuthException::INVALID_NOTICES_DATA);
            self::array($data, 'level', AuthException::INVALID_LEVEL);
            self::int($data, 'stat_points', AuthException::INVALID_STAT_POINTS);
            self::string($data, 'template', AuthException::INVALID_TEMPLATE);

            return new Auth(
                $data['id'],
                $data['name'],
                $data['avatar'],
                new AccountGroup($data['account_group_id']),
                new AccountStatus($data['account_status_id']),
                EnergyFactory::createFromDB($data['energy']),
                (bool)$data['can_like'],
                NoticeCollectionFactory::create($data['notices']),
                LevelFactory::create($data['level'], $sendNoticeAction),
                $data['stat_points'],
                $data['template'],
            );
        } catch (Exception $e) {
            throw new AppException($e->getMessage());
        }
    }
}
