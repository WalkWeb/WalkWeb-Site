<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\Account\AccountException;
use App\Domain\Account\Energy\EnergyFactory;
use App\Domain\Account\Group\AccountGroup;
use App\Domain\Account\MainCharacter\Level\LevelInterface;
use App\Domain\Account\Notice\NoticeCollection;
use App\Domain\Account\Notice\NoticeException;
use App\Domain\Account\Notice\NoticeFactory;
use App\Domain\Account\Status\AccountStatus;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class AuthFactory
{
    use ValidationTrait;

    /**
     * Создает объект реализующий интерфейс AuthInterface на основе массива с данными
     *
     * @param array $data
     * @return AuthInterface
     * @throws AuthException
     * @throws AccountException
     * @throws NoticeException
     * @throws AppException
     */
    public static function create(array $data): AuthInterface
    {
        self::string($data, 'id', AuthException::INVALID_ID);
        self::string($data, 'name', AuthException::INVALID_NAME);
        self::string($data, 'avatar', AuthException::INVALID_AVATAR);
        self::int($data, 'account_group_id', AuthException::INVALID_ACCOUNT_GROUP_ID);
        self::int($data, 'account_status_id', AuthException::INVALID_ACCOUNT_STATUS_ID);
        self::array($data, 'energy', AuthException::INVALID_ENERGY_DATA);
        self::int($data, 'can_like', AuthException::INVALID_CAN_LIKE);
        self::array($data, 'notices', AuthException::INVALID_NOTICES_DATA);
        self::int($data, 'level', AuthException::INVALID_LEVEL);
        self::int($data, 'stat_points', AuthException::INVALID_STAT_POINTS);

        self::intMinMaxValue(
            $data['level'],
            LevelInterface::MIN_LEVEL,
            LevelInterface::MAX_LEVEL,
            AuthException::INVALID_LEVEL_VALUE . LevelInterface::MIN_LEVEL . '-' . LevelInterface::MAX_LEVEL
        );

        $notices = new NoticeCollection();

        foreach ($data['notices'] as $noticeData) {
            if (!is_array($noticeData)) {
                throw new AuthException(AuthException::INVALID_NOTICE_DATA);
            }

            $notices->add(NoticeFactory::create($noticeData));
        }

        return new Auth(
            $data['id'],
            $data['name'],
            $data['avatar'],
            new AccountGroup($data['account_group_id']),
            new AccountStatus($data['account_status_id']),
            EnergyFactory::createFromDB($data['energy']),
            (bool)$data['can_like'],
            $notices,
            $data['level'],
            $data['stat_points'],
        );
    }
}