<?php

declare(strict_types=1);

namespace Test\src\Domain\Account;

use App\Domain\Account\AccountException;
use App\Domain\Account\AccountFactory;
use App\Domain\Account\AccountInterface;
use App\Domain\Account\Character\Avatar\AvatarInterface;
use App\Domain\Account\DTO\CreateAccountRequest;
use App\Domain\Account\Group\AccountGroupInterface;
use App\Domain\Account\Status\AccountStatusInterface;
use Exception;
use Ramsey\Uuid\Uuid;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class AccountFactoryTest extends AbstractTest
{
    /**
     * Test on success create object Account from array (database)
     *
     * @dataProvider createSuccessDataProvider
     * @param array $data
     * @param int $expectedMaxUpload
     * @throws AccountException
     * @throws AppException
     */
    public function testAccountFactoryCreateSuccess(array $data, int $expectedMaxUpload): void
    {
        $account = AccountFactory::create($data, $this->getSendNoticeAction());

        self::assertEquals($data['id'], $account->getId());
        self::assertEquals($data['login'], $account->getLogin());
        self::assertEquals($data['name'], $account->getName());
        self::assertEquals($data['avatar'], $account->getAvatar());
        self::assertEquals($data['password'], $account->getPassword());
        self::assertEquals($data['email'], $account->getEmail());
        self::assertEquals((bool)$data['email_verified'], $account->isEmailVerified());
        self::assertEquals((bool)$data['reg_complete'], $account->isRegComplete());
        self::assertEquals($data['auth_token'], $account->getAuthToken());
        self::assertEquals($data['verified_token'], $account->getVerifiedToken());
        self::assertEquals($data['template'], $account->getTemplate());
        self::assertEquals($data['ip'], $account->getIp());
        self::assertEquals($data['ref'], $account->getRef());
        self::assertEquals($data['floor_id'], $account->getFloor()->getId());
        self::assertEquals($data['status_id'], $account->getStatus()->getId());
        self::assertEquals($data['group_id'], $account->getGroup()->getId());
        self::assertEquals($data['upload'], $account->getUpload()->getUpload());

        self::assertEquals($data['user_agent'], $account->getUserAgent());
        self::assertEquals((bool)$data['can_like'], $account->isCanLike());
        self::assertEquals($data['created_at'], $account->getCreatedAt()->format(self::DATE_FORMAT));
        self::assertEquals($data['updated_at'], $account->getUpdatedAt()->format(self::DATE_FORMAT));

        if (array_key_exists('main_character', $data)) {
            self::assertEquals($data['main_character']['character_id'], $account->getMainCharacter()->getId());
            self::assertEquals($data['main_character']['account_id'], $account->getMainCharacter()->getAccountId());
            self::assertEquals($data['main_character']['era_id'], $account->getMainCharacter()->getEra()->getId());
            self::assertEquals($data['main_character']['character_level'], $account->getMainCharacter()->getLevel()->getLevel());
            self::assertEquals($data['main_character']['character_exp'], $account->getMainCharacter()->getLevel()->getExp());
            self::assertEquals($data['main_character']['energy_bonus'], $account->getMainCharacter()->getEnergyBonus());
            self::assertEquals($data['main_character']['upload_bonus'], $account->getMainCharacter()->getUploadBonus());
            self::assertEquals($data['main_character']['character_stat_points'], $account->getMainCharacter()->getLevel()->getStatPoints());

            self::assertEquals($expectedMaxUpload, $account->getUpload()->getUploadMax());
            self::assertEquals($expectedMaxUpload - $data['upload'], $account->getUpload()->getUploadRemainder());
        } else {
            self::assertEquals(AccountInterface::UPLOAD_MAX_BASE, $account->getUpload()->getUploadMax());
            self::assertEquals(AccountInterface::UPLOAD_MAX_BASE - $data['upload'], $account->getUpload()->getUploadRemainder());
        }
    }

    /**
     * Test on fail create object Account from array (database)
     *
     * @dataProvider createFailDataProvider
     * @param array $data
     * @param string $error
     * @throws AccountException
     * @throws AppException
     */
    public function testAccountFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($error);
        AccountFactory::create($data, $this->getSendNoticeAction());
    }

    /**
     * Test on success create object Account from array (registration form)
     *
     * @dataProvider createNewSuccessDataProvider
     * @param CreateAccountRequest $request
     * @param AvatarInterface $avatar
     * @throws AppException
     */
    public function testAccountFactoryCreateNewSuccess(CreateAccountRequest $request, AvatarInterface $avatar): void
    {
        $account = AccountFactory::createNew($request, $avatar, KEY);

        self::assertTrue(Uuid::isValid($account->getId()));
        self::assertEquals($request->getLogin(), $account->getLogin());
        self::assertEquals($request->getLogin(), $account->getName());
        self::assertEquals($avatar->getOriginUrl(), $account->getAvatar());
        self::assertTrue(password_verify($request->getPassword() . KEY, $account->getPassword()));
        self::assertEquals($request->getEmail(), $account->getEmail());
        self::assertFalse($account->isEmailVerified());
        self::assertFalse($account->isRegComplete());
        self::assertEquals(30, mb_strlen($account->getAuthToken()));
        self::assertEquals(30, mb_strlen($account->getVerifiedToken()));
        self::assertEquals(TEMPLATE_DEFAULT, $account->getTemplate());
        self::assertEquals($request->getIp(), $account->getIp());
        self::assertEquals($request->getReferral(), $account->getRef());
        self::assertEquals($request->getFloor(), $account->getFloor()->getId());
        self::assertEquals(AccountStatusInterface::ACTIVE, $account->getStatus()->getId());
        self::assertEquals(AccountGroupInterface::USER, $account->getGroup()->getId());
        self::assertEquals(0, $account->getUpload()->getUpload());
        self::assertEquals(AccountInterface::UPLOAD_MAX_BASE, $account->getUpload()->getUploadMax());
        self::assertEquals(AccountInterface::UPLOAD_MAX_BASE, $account->getUpload()->getUploadRemainder());
        self::assertEquals($request->getUserAgent(), $account->getUserAgent());
        self::assertTrue($account->isCanLike());
        // TODO Тест иногда падает из-за разницы во времени на 1 секунду, можно делать не точное сравнение, а разницу, и смотреть, что разница не более 1 секунды
        //self::assertEquals((new DateTime())->format(self::DATE_FORMAT), $account->getCreatedAt()->format(self::DATE_FORMAT));
        //self::assertEquals((new DateTime())->format(self::DATE_FORMAT), $account->getUpdatedAt()->format(self::DATE_FORMAT));
    }

    /**
     * @dataProvider createRequestSuccessDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testAccountFactoryCreateRequestSuccess(array $data): void
    {
        $request = AccountFactory::createRequest($data);

        self::assertEquals($data['login'], $request->getLogin());
        self::assertEquals($data['email'], $request->getEmail());
        self::assertEquals($data['password'], $request->getPassword());
        self::assertEquals((int)$data['floor_id'], $request->getFloor());
        self::assertEquals((int)$data['genesis_id'], $request->getGenesis());
        self::assertEquals((int)$data['profession_id'], $request->getProfession());
        self::assertEquals((int)$data['avatar_id'], $request->getAvatar());
        self::assertEquals($data['ref'], $request->getReferral());
        self::assertEquals($data['user_agent'], $request->getUserAgent());
        self::assertEquals($data['ip'], $request->getIp());
    }

    /**
     * @dataProvider createRequestFailDataProvider
     * @param array $data
     * @param string $error
     * @throws AppException
     */
    public function testAccountFactoryCreateRequestFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        AccountFactory::createRequest($data);
    }

    /**
     * @return array
     */
    public function createSuccessDataProvider(): array
    {
        return [
            [
                // no main character
                [
                    'id'                => self::DEMO_USER,
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                0,
            ],
            [
                // + main character
                [
                    'id'                => self::DEMO_USER,
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                    'main_character'    => [
                        'character_id'          => 'e7c3effa-1dbf-42af-a105-17eb06c7a6e0',
                        'account_id'            => self::DEMO_USER,
                        'era_id'                => 1,
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'energy_bonus'          => 0,
                        'upload_bonus'          => 0,
                        'character_stat_points' => 0,
                    ],
                ],
                AccountInterface::UPLOAD_MAX_BASE,
            ],
            [
                // upload bonus
                [
                    'id'                => self::DEMO_USER,
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                    'main_character'    => [
                        'character_id'          => 'e7c3effa-1dbf-42af-a105-17eb06c7a6e0',
                        'account_id'            => self::DEMO_USER,
                        'era_id'                => 1,
                        'character_level'       => 5,
                        'character_exp'         => 0,
                        'energy_bonus'          => 0,
                        'upload_bonus'          => 3,
                        'character_stat_points' => 0,
                    ],
                ],
                AccountInterface::UPLOAD_MAX_BASE + (4 * AccountInterface::UPLOAD_PER_LEVEL) + (3 * AccountInterface::UPLOAD_PER_STAT),
            ],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function createFailDataProvider(): array
    {
        return [
            // miss id
            [
                [
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_ID,
            ],
            // id invalid type
            [
                [
                    'id'                => 123,
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_ID,
            ],
            // id invalid uuid
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e4913',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_ID,
            ],
            // miss login
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_LOGIN,
            ],
            // login invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => true,
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_LOGIN,
            ],
            // login over min length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => self::generateString(AccountInterface::LOGIN_MIN_LENGTH - 1),
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_LOGIN_LENGTH . AccountInterface::LOGIN_MIN_LENGTH . '-' . AccountInterface::LOGIN_MAX_LENGTH,
            ],
            // login over max length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => self::generateString(AccountInterface::LOGIN_MAX_LENGTH + 1),
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_LOGIN_LENGTH . AccountInterface::LOGIN_MIN_LENGTH . '-' . AccountInterface::LOGIN_MAX_LENGTH,
            ],
            // login invalid symbol
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'Login&',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_LOGIN_SYMBOL,
            ],

            // miss name
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_NAME,
            ],
            // name invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => [],
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_NAME,
            ],
            // name over min length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => self::generateString(AccountInterface::NAME_MIN_LENGTH - 1),
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_NAME_LENGTH . AccountInterface::NAME_MIN_LENGTH . '-' . AccountInterface::NAME_MAX_LENGTH,
            ],
            // name over max length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => self::generateString(AccountInterface::NAME_MAX_LENGTH + 1),
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_NAME_LENGTH . AccountInterface::NAME_MIN_LENGTH . '-' . AccountInterface::NAME_MAX_LENGTH,
            ],
            // name invalid symbol
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'Name&',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_NAME_SYMBOL,
            ],
            // miss avatar
            [
                [
                    'id'                => self::DEMO_USER,
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_AVATAR,
            ],
            // avatar invalid type
            [
                [
                    'id'                => self::DEMO_USER,
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => 12,
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_AVATAR,
            ],
            // avatar over min length
            [
                [
                    'id'                => self::DEMO_USER,
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => self::generateString(AccountInterface::AVATAR_MIN_LENGTH - 1),
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_AVATAR_LENGTH . AccountInterface::AVATAR_MIN_LENGTH . '-' . AccountInterface::AVATAR_MAX_LENGTH,
            ],
            // avatar over max length
            [
                [
                    'id'                => self::DEMO_USER,
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => self::generateString(AccountInterface::AVATAR_MAX_LENGTH + 1),
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_AVATAR_LENGTH . AccountInterface::AVATAR_MIN_LENGTH . '-' . AccountInterface::AVATAR_MAX_LENGTH,
            ],
            // miss password
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_PASSWORD,
            ],
            // password invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => false,
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_PASSWORD,
            ],
            // password over min length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => self::generateString(AccountInterface::PASSWORD_MIN_LENGTH - 1),
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_PASSWORD_LENGTH . AccountInterface::PASSWORD_MIN_LENGTH . '-' . AccountInterface::PASSWORD_MAX_LENGTH,
            ],
            // password over max length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => self::generateString(AccountInterface::PASSWORD_MAX_LENGTH + 1),
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_PASSWORD_LENGTH . AccountInterface::PASSWORD_MIN_LENGTH . '-' . AccountInterface::PASSWORD_MAX_LENGTH,
            ],

            // miss email
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_EMAIL,
            ],
            // email invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 123,
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_EMAIL,
            ],
            // email over min length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => self::generateString(AccountInterface::EMAIL_MIN_LENGTH - 1),
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_EMAIL_LENGTH . AccountInterface::EMAIL_MIN_LENGTH . '-' . AccountInterface::EMAIL_MAX_LENGTH,
            ],
            // email over max length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => self::generateString(AccountInterface::EMAIL_MAX_LENGTH + 1),
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_EMAIL_LENGTH . AccountInterface::EMAIL_MIN_LENGTH . '-' . AccountInterface::EMAIL_MAX_LENGTH,
            ],
            // invalid email
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'email_email',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_EMAIL_SYMBOL,
            ],

            // miss email_verified
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_EMAIL_VERIFIED,
            ],
            // email_verified invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => '1',
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_EMAIL_VERIFIED,
            ],

            // miss reg_complete
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_REG_COMPLETE,
            ],
            // reg_complete invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => true,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_REG_COMPLETE,
            ],

            // miss auth_token
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_AUTH_TOKEN,
            ],
            // auth_token invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 100,
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_AUTH_TOKEN,
            ],
            // auth_token over min length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => self::generateString(AccountInterface::AUTH_TOKEN_MIN_LENGTH - 1),
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_AUTH_TOKEN_LENGTH . AccountInterface::AUTH_TOKEN_MIN_LENGTH . '-' . AccountInterface::AUTH_TOKEN_MAX_LENGTH,
            ],
            // auth_token over max length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => self::generateString(AccountInterface::AUTH_TOKEN_MAX_LENGTH + 1),
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_AUTH_TOKEN_LENGTH . AccountInterface::AUTH_TOKEN_MIN_LENGTH . '-' . AccountInterface::AUTH_TOKEN_MAX_LENGTH,
            ],

            // miss verified_token
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_VERIFIED_TOKEN,
            ],
            // verified_token invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => false,
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_VERIFIED_TOKEN,
            ],
            // verified_token over min length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => self::generateString(AccountInterface::VERIFIED_TOKEN_MIN_LENGTH - 1),
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_VERIFIED_TOKEN_LENGTH . AccountInterface::AUTH_TOKEN_MIN_LENGTH . '-' . AccountInterface::AUTH_TOKEN_MAX_LENGTH,
            ],
            // verified_token over max length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => self::generateString(AccountInterface::VERIFIED_TOKEN_MAX_LENGTH + 1),
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_VERIFIED_TOKEN_LENGTH . AccountInterface::AUTH_TOKEN_MIN_LENGTH . '-' . AccountInterface::AUTH_TOKEN_MAX_LENGTH,
            ],

            // miss template
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_TEMPLATE,
            ],
            // template invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 123,
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_TEMPLATE,
            ],
            // template over min length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => self::generateString(AccountInterface::TEMPLATE_MIN_LENGTH - 1),
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_TEMPLATE_LENGTH . AccountInterface::TEMPLATE_MIN_LENGTH . '-' . AccountInterface::TEMPLATE_MAX_LENGTH,
            ],
            // template over max length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => self::generateString(AccountInterface::TEMPLATE_MAX_LENGTH + 1),
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_TEMPLATE_LENGTH . AccountInterface::TEMPLATE_MIN_LENGTH . '-' . AccountInterface::TEMPLATE_MAX_LENGTH,
            ],

            // miss ip
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_IP,
            ],
            // ip invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => 123,
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_IP,
            ],
            // ip over min length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => self::generateString(AccountInterface::IP_MIN_LENGTH - 1),
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_IP_LENGTH . AccountInterface::IP_MIN_LENGTH . '-' . AccountInterface::IP_MAX_LENGTH,
            ],
            // ip over max length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => self::generateString(AccountInterface::IP_MAX_LENGTH + 1),
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_IP_LENGTH . AccountInterface::IP_MIN_LENGTH . '-' . AccountInterface::IP_MAX_LENGTH,
            ],

            // miss ref
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_REF,
            ],
            // ref invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => true,
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_REF,
            ],
            // ref over max length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => self::generateString(AccountInterface::REF_MAX_LENGTH + 1),
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_REF_LENGTH . AccountInterface::REF_MIN_LENGTH . '-' . AccountInterface::REF_MAX_LENGTH,
            ],

            // miss floor_id
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_FLOOR_ID,
            ],
            // floor_id invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => '1',
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_FLOOR_ID,
            ],
            // undefined floor_id
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 5,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::UNKNOWN_FLOOR_ID,
            ],

            // miss status_id
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_STATUS_ID,
            ],
            // status_id invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => '1',
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_STATUS_ID,
            ],
            // unknown status_id
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 5,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::UNKNOWN_STATUS_ID,
            ],

            // miss group_id
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_GROUP_ID,
            ],
            // group_id invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => '10',
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_GROUP_ID,
            ],
            // unknown group_id
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 67,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::UNKNOWN_GROUP_ID,
            ],

            // miss upload
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_UPLOAD,
            ],
            // upload invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => [0],
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_UPLOAD,
            ],
            // upload over min value
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => AccountInterface::UPLOAD_MIN_VALUE - 1,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_UPLOAD_VALUE . AccountInterface::UPLOAD_MIN_VALUE . '-' . AccountInterface::UPLOAD_MAX_VALUE,
            ],
            // upload over max value
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => AccountInterface::UPLOAD_MAX_VALUE + 1,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_UPLOAD_VALUE . AccountInterface::UPLOAD_MIN_VALUE . '-' . AccountInterface::UPLOAD_MAX_VALUE,
            ],

            // miss user_agent
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_USER_AGENT,
            ],
            // user_agent invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 0,
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_USER_AGENT,
            ],
            // user_agent over max length
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => self::generateString(AccountInterface::USER_AGENT_MAX_LENGTH + 1),
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_USER_AGENT_LENGTH . AccountInterface::USER_AGENT_MIN_LENGTH . '-' . AccountInterface::USER_AGENT_MAX_LENGTH,
            ],
            // miss can_like
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_CAN_LIKE,
            ],
            // can_like invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => '1',
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_CAN_LIKE,
            ],

            // miss created_at
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_CREATED_AT,
            ],
            // created_at invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => false,
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_CREATED_AT,
            ],
            // created_at invalid date
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-99-99 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_CREATED_AT,
            ],

            // miss updated_at
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_UPDATED_AT,
            ],
            // updated_at invalid type
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => false,
                ],
                AccountException::INVALID_UPDATED_AT,
            ],
            // updated_at invalid date
            [
                [
                    'id'                => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-99-99 11:00:00',
                ],
                AccountException::INVALID_UPDATED_AT,
            ],

            // main_character invalid type
            [
                [
                    'id'                => self::DEMO_USER,
                    'login'             => 'LoginUser',
                    'name'              => 'NameUser',
                    'avatar'            => '/img/avatars/it/analyst/male/04.jpg',
                    'password'          => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'             => 'mail1@mail.com',
                    'email_verified'    => 1,
                    'reg_complete'      => 1,
                    'auth_token'        => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token'    => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'          => 'default',
                    'ip'                => '127.0.0.1',
                    'ref'               => 'ref_link1',
                    'floor_id'          => 1,
                    'status_id'         => 1,
                    'group_id'          => 10,
                    'upload'            => 0,
                    'user_agent'        => 'undefined',
                    'can_like'          => 1,
                    'main_character'    => '123',
                    'created_at'        => '2020-12-25 11:00:00',
                    'updated_at'        => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_MAIN_CHARACTER,
            ],
        ];
    }

    /**
     * @return array
     * @throws AppException
     */
    public function createNewSuccessDataProvider(): array
    {
        return [
            [
                new CreateAccountRequest(
                    'User',
                    'mail1@gmail.com',
                    '123456',
                    1,
                    3,
                    3,
                    16,
                    'ref_link1',
                    'undefined',
                    '127.0.0.1',
                ),
                $this->getAvatar(),
            ],
        ];
    }

    /**
     * @return array
     */
    public function createRequestSuccessDataProvider(): array
    {
        return [
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
            ],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function createRequestFailDataProvider(): array
    {
        return [
            // miss login
            [
                [
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_LOGIN,
            ],
            // login invalid type
            [
                [
                    'login'         => 11111,
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_LOGIN,
            ],
            // login over min length
            [
                [
                    'login'         => self::generateString(AccountInterface::LOGIN_MIN_LENGTH - 1),
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_LOGIN_LENGTH . AccountInterface::LOGIN_MIN_LENGTH . '-' . AccountInterface::LOGIN_MAX_LENGTH,
            ],
            // login over max length
            [
                [
                    'login'         => self::generateString(AccountInterface::LOGIN_MAX_LENGTH + 1),
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_LOGIN_LENGTH . AccountInterface::LOGIN_MIN_LENGTH . '-' . AccountInterface::LOGIN_MAX_LENGTH,
            ],
            // login invalid symbol
            [
                [
                    'login'         => 'Login&',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_LOGIN_SYMBOL,
            ],

            // miss email
            [
                [
                    'login'         => 'login',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_EMAIL,
            ],
            // email invalid type
            [
                [
                    'login'         => 'login',
                    'email'         => null,
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_EMAIL,
            ],
            // email over min length
            [
                [
                    'login'         => 'login',
                    'email'         => self::generateString(AccountInterface::EMAIL_MIN_LENGTH - 1),
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_EMAIL_LENGTH . AccountInterface::EMAIL_MIN_LENGTH . '-' . AccountInterface::EMAIL_MAX_LENGTH,
            ],
            // email over max length
            [
                [
                    'login'         => 'login',
                    'email'         => self::generateString(AccountInterface::EMAIL_MAX_LENGTH + 1),
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_EMAIL_LENGTH . AccountInterface::EMAIL_MIN_LENGTH . '-' . AccountInterface::EMAIL_MAX_LENGTH,
            ],
            // invalid email
            [
                [
                    'login'         => 'login',
                    'email'         => 'email_email',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_EMAIL_SYMBOL,
            ],

            // miss password
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_PASSWORD,
            ],
            // password invalid type
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => true,
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_PASSWORD,
            ],
            // password over min length
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => self::generateString(AccountInterface::PASSWORD_MIN_LENGTH - 1),
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_PASSWORD_LENGTH . AccountInterface::PASSWORD_MIN_LENGTH . '-' . AccountInterface::PASSWORD_MAX_LENGTH,
            ],
            // password over max length
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => self::generateString(AccountInterface::PASSWORD_MAX_LENGTH + 1),
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_PASSWORD_LENGTH . AccountInterface::PASSWORD_MIN_LENGTH . '-' . AccountInterface::PASSWORD_MAX_LENGTH,
            ],

            // miss floor_id
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_REQUEST_FLOOR_ID,
            ],
            // floor_id invalid type
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => 3,
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_REQUEST_FLOOR_ID,
            ],

            // miss genesis_id
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_GENESIS_ID,
            ],
            // genesis_id invalid type
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => 3,
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_GENESIS_ID,
            ],

            // miss profession_id
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_PROFESSION_ID,
            ],
            // profession_id invalid type
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => 3,
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_PROFESSION_ID,
            ],

            // miss avatar_id
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_AVATAR_ID,
            ],
            // avatar_id invalid type
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => 21,
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_AVATAR_ID,
            ],

            // miss ref
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_REF,
            ],
            // ref invalid type
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => null,
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_REF,
            ],
            // ref over max length
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => self::generateString(AccountInterface::REF_MAX_LENGTH + 1),
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_REF_LENGTH . AccountInterface::REF_MIN_LENGTH . '-' . AccountInterface::REF_MAX_LENGTH,
            ],

            // miss user_agent
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_USER_AGENT,
            ],
            // user_agent invalid type
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => true,
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_USER_AGENT,
            ],
            // user_agent over max length
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => self::generateString(AccountInterface::USER_AGENT_MAX_LENGTH + 1),
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_USER_AGENT_LENGTH . AccountInterface::USER_AGENT_MIN_LENGTH . '-' . AccountInterface::USER_AGENT_MAX_LENGTH,
            ],

            // miss ip
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                ],
                AccountException::INVALID_IP,
            ],
            // ip invalid type
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => 12.32,
                ],
                AccountException::INVALID_IP,
            ],
            // ip over min length
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => self::generateString(AccountInterface::IP_MIN_LENGTH - 1),
                ],
                AccountException::INVALID_IP_LENGTH . AccountInterface::IP_MIN_LENGTH . '-' . AccountInterface::IP_MAX_LENGTH,
            ],
            // ip over max length
            [
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '21',
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => self::generateString(AccountInterface::IP_MAX_LENGTH + 1),
                ],
                AccountException::INVALID_IP_LENGTH . AccountInterface::IP_MIN_LENGTH . '-' . AccountInterface::IP_MAX_LENGTH,
            ],
        ];
    }
}
