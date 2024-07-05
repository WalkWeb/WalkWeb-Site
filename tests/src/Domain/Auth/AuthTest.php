<?php

declare(strict_types=1);

namespace Test\src\Domain\Auth;

use App\Domain\Account\AccountException;
use App\Domain\Account\Energy\Energy;
use App\Domain\Account\Group\AccountGroup;
use App\Domain\Account\Notice\NoticeCollection;
use App\Domain\Account\Status\AccountStatus;
use App\Domain\Auth\Auth;
use Test\AbstractTest;

class AuthTest extends AbstractTest
{
    /**
     * Тест на успешное создание объекта Auth
     *
     * @throws AccountException
     */
    public function testAuthCreateSuccess(): void
    {
        $id = 'fed7e105-316d-4f9b-bfc4-7d70dba0b680';
        $name = 'Name';
        $avatar = 'avatar.png';
        $group = new AccountGroup(10);
        $status = new AccountStatus(2);
        $energy = new Energy(
            '8d3af2e4-b706-4956-b59f-6d39526dc6dc',
            100,
            150,
            (float)microtime(true),
            (float)microtime(true),
            0
        );
        $canLike = true;
        $notices = new NoticeCollection();
        $level = 15;
        $statPoints = 10;

        $auth = new Auth($id, $name, $avatar, $group, $status, $energy, $canLike, $notices, $level, $statPoints);

        self::assertEquals($id, $auth->getId());
        self::assertEquals($name, $auth->getName());
        self::assertEquals($avatar, $auth->getAvatar());
        self::assertEquals($group, $auth->getGroup());
        self::assertEquals($status, $auth->getStatus());
        self::assertEquals($energy, $auth->getEnergy());
        self::assertEquals($canLike, $auth->isCanLike());
        self::assertEquals($level, $auth->getLevel());
        self::assertEquals($statPoints, $auth->getStatPoints());
    }

    /**
     * Тест на установку нового значения statPoints
     *
     * @throws AccountException
     */
    public function testAuthSetStatPoints(): void
    {
        $auth = new Auth(
            'abc',
            'name',
            'avatar',
            new AccountGroup(10),
            new AccountStatus(2),
            new Energy(
                '8d3af2e4-b706-4956-b59f-6d39526dc6dc',
                100,
                150,
                (float)microtime(true),
                (float)microtime(true),
                0
            ),
            true,
            new NoticeCollection(),
            5,
            $statPoints = 0
        );

        self::assertEquals($statPoints, $auth->getStatPoints());

        $auth->setStatPoints($newStatPoints = 5);

        self::assertEquals($newStatPoints, $auth->getStatPoints());
    }
}
