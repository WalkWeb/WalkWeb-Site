<?php

declare(strict_types=1);

namespace Test\src\Domain\Auth;

use App\Domain\Account\Energy\Energy;
use App\Domain\Account\Group\AccountGroup;
use App\Domain\Account\MainCharacter\Level\MainLevel;
use App\Domain\Account\Notice\NoticeCollection;
use App\Domain\Account\Status\AccountStatus;
use App\Domain\Account\Upload\AccountUpload;
use App\Domain\Auth\Auth;
use Exception;
use Test\AbstractTest;

class AuthTest extends AbstractTest
{
    /**
     * Тест на успешное создание объекта Auth
     *
     * @throws Exception
     */
    public function testAuthCreateSuccess(): void
    {
        $id = 'fed7e105-316d-4f9b-bfc4-7d70dba0b680';
        $characterId = '5585ab44-f75a-4590-a162-e46124aecedc';
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
        $level = new MainLevel($id, $characterId, 1, 0, 0, $this->getSendNoticeAction());
        $statPoints = 10;
        $template = 'default';
        $emailVerified = true;
        $upload = new AccountUpload(0, 10000);

        $auth = new Auth(
            $id,
            $name,
            $avatar,
            $group,
            $status,
            $energy,
            $canLike,
            $notices,
            $level,
            $statPoints,
            $template,
            $emailVerified,
            $upload
        );

        self::assertEquals($id, $auth->getId());
        self::assertEquals($name, $auth->getName());
        self::assertEquals($avatar, $auth->getAvatar());
        self::assertEquals($group, $auth->getGroup());
        self::assertEquals($status, $auth->getStatus());
        self::assertEquals($energy, $auth->getEnergy());
        self::assertEquals($canLike, $auth->isCanLike());
        self::assertEquals($level, $auth->getLevel());
        self::assertEquals($statPoints, $auth->getStatPoints());
        self::assertEquals($template, $auth->getTemplate());
        self::assertEquals($emailVerified, $auth->isEmailVerified());
        self::assertEquals($upload, $auth->getUpload());
    }

    /**
     * Тест на установку нового значения statPoints
     *
     * @throws Exception
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
            new MainLevel(
                '03796f7c-37b1-4b80-a0ed-3316b36c5518',
                'fb84b694-f169-4770-ae83-9fadb96a9368',
                1,
                0,
                0,
                $this->getSendNoticeAction()
            ),
            $statPoints = 0,
            'default',
            false,
            new AccountUpload(0, 10000)
        );

        self::assertEquals($statPoints, $auth->getStatPoints());

        $auth->setStatPoints($newStatPoints = 5);

        self::assertEquals($newStatPoints, $auth->getStatPoints());
    }
}
