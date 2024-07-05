<?php

declare(strict_types=1);

namespace Test\src\Domain\Account;

use App\Domain\Account\AccountException;
use App\Domain\Account\AccountFactory;
use App\Domain\Account\AccountInterface;
use App\Domain\Account\Group\AccountGroupInterface;
use App\Domain\Account\Status\AccountStatusInterface;
use DateTime;
use Exception;
use Ramsey\Uuid\Uuid;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class AccountFactoryTest extends AbstractTest
{
    /**
     * Test on success create object Account from array (database)
     *
     * @dataProvider createFromDBSuccessDataProvider
     * @param array $data
     * @throws AccountException
     * @throws AppException
     */
    public function testAccountFactoryCreateFromDBSuccess(array $data): void
    {
        $account = AccountFactory::createFromDB($data);

        self::assertEquals($data['id'], $account->getId());
        self::assertEquals($data['login'], $account->getLogin());
        self::assertEquals($data['name'], $account->getName());
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
        self::assertEquals(AccountInterface::UPLOAD_MAX_BASE, $account->getUpload()->getUploadMax());
        self::assertEquals(AccountInterface::UPLOAD_MAX_BASE - $data['upload'], $account->getUpload()->getUploadRemainder());
        self::assertEquals($data['user_agent'], $account->getUserAgent());
        self::assertEquals((bool)$data['can_like'], $account->isCanLike());
        self::assertEquals($data['created_at'], $account->getCreatedAt()->format(self::DATE_FORMAT));
        self::assertEquals($data['updated_at'], $account->getUpdatedAt()->format(self::DATE_FORMAT));
    }

    /**
     * Test on fail create object Account from array (database)
     *
     * @dataProvider createFromDBFailDataProvider
     * @param array $data
     * @param string $error
     * @throws AccountException
     * @throws AppException
     */
    public function testAccountFactoryCreateFromDBFail(array $data, string $error): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($error);
        AccountFactory::createFromDB($data);
    }

    /**
     * Test on success create object Account from array (registration form)
     *
     * @dataProvider createNewSuccessDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testAccountFactoryCreateNewSuccess(array $data): void
    {
        $account = AccountFactory::createNew($data, KEY);

        self::assertTrue(Uuid::isValid($account->getId()));
        self::assertEquals($data['login'], $account->getLogin());
        self::assertEquals($data['login'], $account->getName());
        self::assertTrue(password_verify($data['password'] . KEY, $account->getPassword()));
        self::assertEquals($data['email'], $account->getEmail());
        self::assertFalse($account->isEmailVerified());
        self::assertFalse($account->isRegComplete());
        self::assertEquals(30, mb_strlen($account->getAuthToken()));
        self::assertEquals(30, mb_strlen($account->getVerifiedToken()));
        self::assertEquals($data['template'], $account->getTemplate());
        self::assertEquals($data['ip'], $account->getIp());
        self::assertEquals($data['ref'], $account->getRef());
        self::assertEquals($data['floor_id'], $account->getFloor()->getId());
        self::assertEquals(AccountStatusInterface::ACTIVE, $account->getStatus()->getId());
        self::assertEquals(AccountGroupInterface::USER, $account->getGroup()->getId());
        self::assertEquals(0, $account->getUpload()->getUpload());
        self::assertEquals(AccountInterface::UPLOAD_MAX_BASE, $account->getUpload()->getUploadMax());
        self::assertEquals(AccountInterface::UPLOAD_MAX_BASE, $account->getUpload()->getUploadRemainder());
        self::assertEquals($data['user_agent'], $account->getUserAgent());
        self::assertTrue($account->isCanLike());
        self::assertEquals((new DateTime())->format(self::DATE_FORMAT), $account->getCreatedAt()->format(self::DATE_FORMAT));
        self::assertEquals((new DateTime())->format(self::DATE_FORMAT), $account->getUpdatedAt()->format(self::DATE_FORMAT));
    }

    /**
     * Test on fail create object Account from array (registration form)
     *
     * @dataProvider createNewFailDataProvider
     * @param array $data
     * @param string $error
     * @throws AppException
     */
    public function testAccountFactoryCreateNewFail(array $data, string $error): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($error);
        AccountFactory::createNew($data, KEY);
    }

    /**
     * @return array
     */
    public function createFromDBSuccessDataProvider(): array
    {
        return [
            [
                [
                    'id'             => '1e3a3b27-12da-4c73-a3a7-b83092705bae',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
            ],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function createFromDBFailDataProvider(): array
    {
        return [
            // miss id
            [
                [
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_ID,
            ],
            // id invalid type
            [
                [
                    'id'             => 123,
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_ID,
            ],
            // id invalid uuid
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e4913',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_ID_VALUE,
            ],
            // miss login
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_LOGIN,
            ],
            // login invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => true,
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_LOGIN,
            ],
            // login over min length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => self::generateString(AccountInterface::LOGIN_MIN_LENGTH - 1),
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_LOGIN_LENGTH . AccountInterface::LOGIN_MIN_LENGTH . '-' . AccountInterface::LOGIN_MAX_LENGTH,
            ],
            // login over max length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => self::generateString(AccountInterface::LOGIN_MAX_LENGTH + 1),
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_LOGIN_LENGTH . AccountInterface::LOGIN_MIN_LENGTH . '-' . AccountInterface::LOGIN_MAX_LENGTH,
            ],
            // login invalid symbol
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'Login&',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_LOGIN_SYMBOL,
            ],

            // miss name
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_NAME,
            ],
            // name invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => [],
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_NAME,
            ],
            // name over min length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'          => self::generateString(AccountInterface::NAME_MIN_LENGTH - 1),
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_NAME_LENGTH . AccountInterface::NAME_MIN_LENGTH . '-' . AccountInterface::NAME_MAX_LENGTH,
            ],
            // name over max length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'          => self::generateString(AccountInterface::NAME_MAX_LENGTH + 1),
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_NAME_LENGTH . AccountInterface::NAME_MIN_LENGTH . '-' . AccountInterface::NAME_MAX_LENGTH,
            ],
            // name invalid symbol
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'Name&',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_NAME_SYMBOL,
            ],

            // miss password
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_PASSWORD,
            ],
            // password invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => false,
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_PASSWORD,
            ],
            // password over min length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => self::generateString(AccountInterface::PASSWORD_MIN_LENGTH - 1),
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_PASSWORD_LENGTH . AccountInterface::PASSWORD_MIN_LENGTH . '-' . AccountInterface::PASSWORD_MAX_LENGTH,
            ],
            // password over max length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => self::generateString(AccountInterface::PASSWORD_MAX_LENGTH + 1),
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_PASSWORD_LENGTH . AccountInterface::PASSWORD_MIN_LENGTH . '-' . AccountInterface::PASSWORD_MAX_LENGTH,
            ],

            // miss email
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_EMAIL,
            ],
            // email invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 123,
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_EMAIL,
            ],
            // email over min length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => self::generateString(AccountInterface::EMAIL_MIN_LENGTH - 1),
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_EMAIL_LENGTH . AccountInterface::EMAIL_MIN_LENGTH . '-' . AccountInterface::EMAIL_MAX_LENGTH,
            ],
            // email over max length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => self::generateString(AccountInterface::EMAIL_MAX_LENGTH + 1),
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_EMAIL_LENGTH . AccountInterface::EMAIL_MIN_LENGTH . '-' . AccountInterface::EMAIL_MAX_LENGTH,
            ],
            // invalid email
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'email_email',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_EMAIL_SYMBOL,
            ],

            // miss email_verified
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_EMAIL_VERIFIED,
            ],
            // email_verified invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => '1',
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_EMAIL_VERIFIED,
            ],

            // miss reg_complete
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_REG_COMPLETE,
            ],
            // reg_complete invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => true,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_REG_COMPLETE,
            ],

            // miss auth_token
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_AUTH_TOKEN,
            ],
            // auth_token invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 100,
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_AUTH_TOKEN,
            ],
            // auth_token over min length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => self::generateString(AccountInterface::AUTH_TOKEN_MIN_LENGTH - 1),
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_AUTH_TOKEN_LENGTH . AccountInterface::AUTH_TOKEN_MIN_LENGTH . '-' . AccountInterface::AUTH_TOKEN_MAX_LENGTH,
            ],
            // auth_token over max length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => self::generateString(AccountInterface::AUTH_TOKEN_MAX_LENGTH + 1),
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_AUTH_TOKEN_LENGTH . AccountInterface::AUTH_TOKEN_MIN_LENGTH . '-' . AccountInterface::AUTH_TOKEN_MAX_LENGTH,
            ],

            // miss verified_token
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_VERIFIED_TOKEN,
            ],
            // verified_token invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => false,
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_VERIFIED_TOKEN,
            ],
            // verified_token over min length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => self::generateString(AccountInterface::VERIFIED_TOKEN_MIN_LENGTH - 1),
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_VERIFIED_TOKEN_LENGTH . AccountInterface::AUTH_TOKEN_MIN_LENGTH . '-' . AccountInterface::AUTH_TOKEN_MAX_LENGTH,
            ],
            // verified_token over max length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => self::generateString(AccountInterface::VERIFIED_TOKEN_MAX_LENGTH + 1),
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_VERIFIED_TOKEN_LENGTH . AccountInterface::AUTH_TOKEN_MIN_LENGTH . '-' . AccountInterface::AUTH_TOKEN_MAX_LENGTH,
            ],

            // miss template
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_TEMPLATE,
            ],
            // template invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 123,
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_TEMPLATE,
            ],
            // template over min length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => self::generateString(AccountInterface::TEMPLATE_MIN_LENGTH - 1),
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_TEMPLATE_LENGTH . AccountInterface::TEMPLATE_MIN_LENGTH . '-' . AccountInterface::TEMPLATE_MAX_LENGTH,
            ],
            // template over max length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => self::generateString(AccountInterface::TEMPLATE_MAX_LENGTH + 1),
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_TEMPLATE_LENGTH . AccountInterface::TEMPLATE_MIN_LENGTH . '-' . AccountInterface::TEMPLATE_MAX_LENGTH,
            ],

            // miss ip
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_IP,
            ],
            // ip invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => 123,
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_IP,
            ],
            // ip over min length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => self::generateString(AccountInterface::IP_MIN_LENGTH - 1),
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_IP_LENGTH . AccountInterface::IP_MIN_LENGTH . '-' . AccountInterface::IP_MAX_LENGTH,
            ],
            // ip over max length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => self::generateString(AccountInterface::IP_MAX_LENGTH + 1),
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_IP_LENGTH . AccountInterface::IP_MIN_LENGTH . '-' . AccountInterface::IP_MAX_LENGTH,
            ],

            // miss ref
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_REF,
            ],
            // ref invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => true,
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_REF,
            ],
            // ref over max length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => self::generateString(AccountInterface::REF_MAX_LENGTH + 1),
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_REF_LENGTH . AccountInterface::REF_MIN_LENGTH . '-' . AccountInterface::REF_MAX_LENGTH,
            ],

            // miss floor_id
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_FLOOR_ID,
            ],
            // floor_id invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => '1',
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_FLOOR_ID,
            ],
            // undefined floor_id
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 5,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::UNKNOWN_FLOOR_ID,
            ],

            // miss status_id
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_STATUS_ID,
            ],
            // status_id invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => '1',
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_STATUS_ID,
            ],
            // unknown status_id
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 5,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::UNKNOWN_STATUS_ID,
            ],

            // miss group_id
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_GROUP_ID,
            ],
            // group_id invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => '10',
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_GROUP_ID,
            ],
            // unknown group_id
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 67,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::UNKNOWN_GROUP_ID,
            ],

            // miss upload
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_UPLOAD,
            ],
            // upload invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => [0],
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_UPLOAD,
            ],
            // upload over min value
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => AccountInterface::UPLOAD_MIN_VALUE - 1,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_UPLOAD_VALUE . AccountInterface::UPLOAD_MIN_VALUE . '-' . AccountInterface::UPLOAD_MAX_VALUE,
            ],
            // upload over max value
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => AccountInterface::UPLOAD_MAX_VALUE + 1,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_UPLOAD_VALUE . AccountInterface::UPLOAD_MIN_VALUE . '-' . AccountInterface::UPLOAD_MAX_VALUE,
            ],

            // miss user_agent
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_USER_AGENT,
            ],
            // user_agent invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 0,
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_USER_AGENT,
            ],
            // user_agent over max length
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => self::generateString(AccountInterface::USER_AGENT_MAX_LENGTH + 1),
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_USER_AGENT_LENGTH . AccountInterface::USER_AGENT_MIN_LENGTH . '-' . AccountInterface::USER_AGENT_MAX_LENGTH,
            ],
            // miss can_like
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_CAN_LIKE,
            ],
            // can_like invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => '1',
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_CAN_LIKE,
            ],

            // miss created_at
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_CREATED_AT,
            ],
            // created_at invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => false,
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_CREATED_AT,
            ],
            // created_at invalid date
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-99-99 11:00:00',
                    'updated_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_CREATED_AT_VALUE,
            ],

            // miss updated_at
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                ],
                AccountException::INVALID_UPDATED_AT,
            ],
            // updated_at invalid type
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => false,
                ],
                AccountException::INVALID_UPDATED_AT,
            ],
            // updated_at invalid date
            [
                [
                    'id'             => 'cb62e415-0630-4bff-b0b8-c2cae0e49138',
                    'login'          => 'LoginUser',
                    'name'           => 'NameUser',
                    'password'       => '$2y$10$QjmBAUvgcu4nAmEnqEEHAebsA3GKS.4V0ngNIvk8t7adq0S/n7Uea',
                    'email'          => 'mail1@mail.com',
                    'email_verified' => 1,
                    'reg_complete'   => 1,
                    'auth_token'     => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5',
                    'verified_token' => 'ISUgTBiTjVht2PIVQqSR52hmeXNs2Z',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'status_id'      => 1,
                    'group_id'       => 10,
                    'upload'         => 0,
                    'user_agent'     => 'undefined',
                    'can_like'       => 1,
                    'created_at'     => '2020-12-25 11:00:00',
                    'updated_at'     => '2020-99-99 11:00:00',
                ],
                AccountException::INVALID_UPDATED_AT_VALUE,
            ],
        ];
    }

    /**
     * @return array
     */
    public function createNewSuccessDataProvider(): array
    {
        return [
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
            ],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function createNewFailDataProvider(): array
    {
        return [
            // miss login
            [
                [
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_LOGIN,
            ],
            // login invalid type
            [
                [
                    'login'          => true,
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_LOGIN,
            ],
            // login over min length
            [
                [
                    'login'          => self::generateString(AccountInterface::LOGIN_MIN_LENGTH - 1),
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_LOGIN_LENGTH . AccountInterface::LOGIN_MIN_LENGTH . '-' . AccountInterface::LOGIN_MAX_LENGTH,
            ],
            // login over max length
            [
                [
                    'login'          => self::generateString(AccountInterface::LOGIN_MAX_LENGTH + 1),
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_LOGIN_LENGTH . AccountInterface::LOGIN_MIN_LENGTH . '-' . AccountInterface::LOGIN_MAX_LENGTH,
            ],
            // login invalid symbol
            [
                [
                    'login'          => 'Login&',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_LOGIN_SYMBOL,
            ],

            // miss password
            [
                [
                    'login'          => 'NameUser',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_PASSWORD,
            ],
            // password invalid type
            [
                [
                    'login'          => 'NameUser',
                    'password'       => 123,
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_PASSWORD,
            ],
            // password over min length
            [
                [
                    'login'          => 'NameUser',
                    'password'       => self::generateString(AccountInterface::PASSWORD_MIN_LENGTH - 1),
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_PASSWORD_LENGTH . AccountInterface::PASSWORD_MIN_LENGTH . '-' . AccountInterface::PASSWORD_MAX_LENGTH,
            ],
            // password over max length
            [
                [
                    'login'          => 'NameUser',
                    'password'       => self::generateString(AccountInterface::PASSWORD_MAX_LENGTH + 1),
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_PASSWORD_LENGTH . AccountInterface::PASSWORD_MIN_LENGTH . '-' . AccountInterface::PASSWORD_MAX_LENGTH,
            ],

            // miss email
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_EMAIL,
            ],
            // email invalid type
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => ['mail1@mail.com'],
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_EMAIL,
            ],
            // email over min length
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => self::generateString(AccountInterface::EMAIL_MIN_LENGTH - 1),
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_EMAIL_LENGTH . AccountInterface::EMAIL_MIN_LENGTH . '-' . AccountInterface::EMAIL_MAX_LENGTH,
            ],
            // email over max length
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => self::generateString(AccountInterface::EMAIL_MAX_LENGTH + 1),
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_EMAIL_LENGTH . AccountInterface::EMAIL_MIN_LENGTH . '-' . AccountInterface::EMAIL_MAX_LENGTH,
            ],
            // invalid email
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'email_email',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_EMAIL_SYMBOL,
            ],

            // miss template
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_TEMPLATE,
            ],
            // template invalid type
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => null,
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_TEMPLATE,
            ],
            // template over min length
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => self::generateString(AccountInterface::TEMPLATE_MIN_LENGTH - 1),
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_TEMPLATE_LENGTH . AccountInterface::TEMPLATE_MIN_LENGTH . '-' . AccountInterface::TEMPLATE_MAX_LENGTH,
            ],
            // template over max length
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => self::generateString(AccountInterface::TEMPLATE_MAX_LENGTH + 1),
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_TEMPLATE_LENGTH . AccountInterface::TEMPLATE_MIN_LENGTH . '-' . AccountInterface::TEMPLATE_MAX_LENGTH,
            ],

            // miss ip
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_IP,
            ],
            // ip invalid type
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => 123,
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_IP,
            ],
            // ip over min length
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => self::generateString(AccountInterface::IP_MIN_LENGTH - 1),
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_IP_LENGTH . AccountInterface::IP_MIN_LENGTH . '-' . AccountInterface::IP_MAX_LENGTH,
            ],
            // ip over max length
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => self::generateString(AccountInterface::IP_MAX_LENGTH + 1),
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_IP_LENGTH . AccountInterface::IP_MIN_LENGTH . '-' . AccountInterface::IP_MAX_LENGTH,
            ],

            // miss ref
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_REF,
            ],
            // ref invalid type
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => true,
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_REF,
            ],
            // ref over max length
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => self::generateString(AccountInterface::REF_MAX_LENGTH + 1),
                    'floor_id'       => 1,
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_REF_LENGTH . AccountInterface::REF_MIN_LENGTH . '-' . AccountInterface::REF_MAX_LENGTH,
            ],

            // miss floor_id
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_FLOOR_ID,
            ],
            // floor_id invalid type
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => '1',
                    'user_agent'     => 'undefined',
                ],
                AccountException::INVALID_FLOOR_ID,
            ],
            // undefined floor_id
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 5,
                    'user_agent'     => 'undefined',
                ],
                AccountException::UNKNOWN_FLOOR_ID,
            ],

            // miss user_agent
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,

                ],
                AccountException::INVALID_USER_AGENT,
            ],
            // user_agent invalid type
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => 0,

                ],
                AccountException::INVALID_USER_AGENT,
            ],
            // user_agent over max length
            [
                [
                    'login'          => 'NameUser',
                    'password'       => '123456',
                    'email'          => 'mail1@mail.com',
                    'template'       => 'default',
                    'ip'             => '127.0.0.1',
                    'ref'            => 'ref_link1',
                    'floor_id'       => 1,
                    'user_agent'     => self::generateString(AccountInterface::USER_AGENT_MAX_LENGTH + 1),
                ],
                AccountException::INVALID_USER_AGENT_LENGTH . AccountInterface::USER_AGENT_MIN_LENGTH . '-' . AccountInterface::USER_AGENT_MAX_LENGTH,
            ],
        ];
    }
}