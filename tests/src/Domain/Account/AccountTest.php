<?php

declare(strict_types=1);

namespace Test\src\Domain\Account;

use App\Domain\Account\Account;
use App\Domain\Account\AccountException;
use App\Domain\Account\Carma\Carma;
use App\Domain\Account\Character\Season\Season;
use App\Domain\Account\Floor\Floor;
use App\Domain\Account\Group\AccountGroup;
use App\Domain\Account\Status\AccountStatus;
use App\Domain\Account\Upload\AccountUpload;
use DateTime;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class AccountTest extends AbstractTest
{
    /**
     * Test on success created Account object
     *
     * @throws AppException
     */
    public function testAccountCreateSuccess(): void
    {
        $id = '345994bd-6219-4d35-8c31-121222574778';
        $login = 'Login';
        $name = 'Name';
        $avatar = 'avatar.png';
        $password = 'password_hash';
        $email = 'mail@mail.ru';
        $emailVerified = false;
        $regComplete = false;
        $authToken = 'auth_token';
        $verifiedToken = 'verified_token';
        $template = 'template';
        $ip = '127.0.0.1';
        $ref = 'ref_link';
        $userAgent = 'undefined';
        $canLike = true;
        $postCount = 123;
        $commentCount = 345;
        $floor = new Floor(1);
        $status = new AccountStatus(1);
        $group = new AccountGroup(10);
        $upload = new AccountUpload(0, 10000);
        $carma = new Carma('0d462231-3a33-4df6-95e5-ee7b99d27530', $id, new Season(ACTIVE_SEASON), 20, 5);
        $createdAt = new DateTime('2020-12-25 20:00:00');
        $updatedAt = new DateTime('2020-12-25 20:00:00');

        $account = new Account(
            $id,
            $login,
            $name,
            $avatar,
            $password,
            $email,
            $emailVerified,
            $regComplete,
            $authToken,
            $verifiedToken,
            $template,
            $ip,
            $ref,
            $userAgent,
            $canLike,
            $postCount,
            $commentCount,
            $floor,
            $status,
            $group,
            $upload,
            $carma,
            $createdAt,
            $updatedAt,
            null,
        );

        self::assertEquals($id, $account->getId());
        self::assertEquals($login, $account->getLogin());
        self::assertEquals($name, $account->getName());
        self::assertEquals($avatar, $account->getAvatar());
        self::assertEquals($password, $account->getPassword());
        self::assertEquals($email, $account->getEmail());
        self::assertEquals($emailVerified, $account->isEmailVerified());
        self::assertEquals($regComplete, $account->isRegComplete());
        self::assertEquals($authToken, $account->getAuthToken());
        self::assertEquals($verifiedToken, $account->getVerifiedToken());
        self::assertEquals($template, $account->getTemplate());
        self::assertEquals($ip, $account->getIp());
        self::assertEquals($ref, $account->getRef());
        self::assertEquals($userAgent, $account->getUserAgent());
        self::assertEquals($canLike, $account->isCanLike());
        self::assertEquals($postCount, $account->getPostCount());
        self::assertEquals($commentCount, $account->getCommentCount());
        self::assertEquals($floor, $account->getFloor());
        self::assertEquals($status, $account->getStatus());
        self::assertEquals($group, $account->getGroup());
        self::assertEquals($upload, $account->getUpload());
        self::assertEquals($carma, $account->getCarma());
        self::assertEquals($createdAt, $account->getCreatedAt());
        self::assertEquals($updatedAt, $account->getUpdatedAt());

        $account->emailVerified();
        $account->setTemplate($newTemplate = 'new_template');

        self::assertTrue($account->isEmailVerified());
        self::assertTrue($account->isRegComplete());
        self::assertEquals($newTemplate, $account->getTemplate());

        $this->expectException(AppException::class);
        $this->expectExceptionMessage(AccountException::MISS_MAIN_CHARACTER);
        $account->getMainCharacter();
    }
}
