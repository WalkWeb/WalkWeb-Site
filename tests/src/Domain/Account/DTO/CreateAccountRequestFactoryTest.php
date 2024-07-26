<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\DTO;

use App\Domain\Account\AccountException;
use App\Domain\Account\DTO\CreateAccountRequestFactory;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CreateAccountRequestFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testCreateAccountRequestFactorySuccess(array $data): void
    {
        $request = CreateAccountRequestFactory::create($data);

        self::assertEquals($data['login'], $request->getLogin());
        self::assertEquals($data['email'], $request->getEmail());
        self::assertEquals($data['password'], $request->getPassword());
        self::assertEquals($data['floor_id'], $request->getFloor());
        self::assertEquals($data['genesis_id'], $request->getGenesis());
        self::assertEquals($data['profession_id'], $request->getProfession());
        self::assertEquals($data['avatar_id'], $request->getAvatar());
        self::assertEquals($data['ref'], $request->getReferral());
        self::assertEquals($data['user_agent'], $request->getUserAgent());
        self::assertEquals($data['ip'], $request->getIp());

        self::assertEquals($data, $request->toArray());
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     * @throws AppException
     */
    public function testCreateAccountRequestFactoryFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        CreateAccountRequestFactory::create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'login'         => 'login',
                    'email'         => 'email',
                    'password'      => 'password',
                    'floor_id'      => 2,
                    'genesis_id'    => 3,
                    'profession_id' => 3,
                    'avatar_id'     => 21,
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function failDataProvider(): array
    {
        return [
            // miss login
            [
                [
                    'email'         => 'email',
                    'password'      => 'password',
                    'floor_id'      => 2,
                    'genesis_id'    => 3,
                    'profession_id' => 3,
                    'avatar_id'     => 21,
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
                    'email'         => 'email',
                    'password'      => 'password',
                    'floor_id'      => 2,
                    'genesis_id'    => 3,
                    'profession_id' => 3,
                    'avatar_id'     => 21,
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_LOGIN,
            ],

            // miss email
            [
                [
                    'login'         => 'login',
                    'password'      => 'password',
                    'floor_id'      => 2,
                    'genesis_id'    => 3,
                    'profession_id' => 3,
                    'avatar_id'     => 21,
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
                    'floor_id'      => 2,
                    'genesis_id'    => 3,
                    'profession_id' => 3,
                    'avatar_id'     => 21,
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_EMAIL,
            ],

            // miss password
            [
                [
                    'login'         => 'login',
                    'email'         => 'email',
                    'floor_id'      => 2,
                    'genesis_id'    => 3,
                    'profession_id' => 3,
                    'avatar_id'     => 21,
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
                    'email'         => 'email',
                    'password'      => true,
                    'floor_id'      => 2,
                    'genesis_id'    => 3,
                    'profession_id' => 3,
                    'avatar_id'     => 21,
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_PASSWORD,
            ],

            // miss floor_id
            [
                [
                    'login'         => 'login',
                    'email'         => 'email',
                    'password'      => 'password',
                    'genesis_id'    => 3,
                    'profession_id' => 3,
                    'avatar_id'     => 21,
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_FLOOR_ID,
            ],
            // floor_id invalid type
            [
                [
                    'login'         => 'login',
                    'email'         => 'email',
                    'password'      => 'password',
                    'floor_id'      => '',
                    'genesis_id'    => 3,
                    'profession_id' => 3,
                    'avatar_id'     => 21,
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_FLOOR_ID,
            ],

            // miss genesis_id
            [
                [
                    'login'         => 'login',
                    'email'         => 'email',
                    'password'      => 'password',
                    'floor_id'      => 2,
                    'profession_id' => 3,
                    'avatar_id'     => 21,
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
                    'email'         => 'email',
                    'password'      => 'password',
                    'floor_id'      => 2,
                    'genesis_id'    => [3],
                    'profession_id' => 3,
                    'avatar_id'     => 21,
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
                    'email'         => 'email',
                    'password'      => 'password',
                    'floor_id'      => 2,
                    'genesis_id'    => 3,
                    'avatar_id'     => 21,
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
                    'email'         => 'email',
                    'password'      => 'password',
                    'floor_id'      => 2,
                    'genesis_id'    => 3,
                    'profession_id' => null,
                    'avatar_id'     => 21,
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
                    'email'         => 'email',
                    'password'      => 'password',
                    'floor_id'      => 2,
                    'genesis_id'    => 3,
                    'profession_id' => 3,
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
                    'email'         => 'email',
                    'password'      => 'password',
                    'floor_id'      => 2,
                    'genesis_id'    => 3,
                    'profession_id' => 3,
                    'avatar_id'     => '21',
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
                    'email'         => 'email',
                    'password'      => 'password',
                    'floor_id'      => 2,
                    'genesis_id'    => 3,
                    'profession_id' => 3,
                    'avatar_id'     => 21,
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_REF,
            ],
            // ref invalid type
            [
                [
                    'login'         => 'login',
                    'email'         => 'email',
                    'password'      => 'password',
                    'floor_id'      => 2,
                    'genesis_id'    => 3,
                    'profession_id' => 3,
                    'avatar_id'     => 21,
                    'ref'           => null,
                    'user_agent'    => 'user info',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_REF,
            ],

            // miss user_agent
            [
                [
                    'login'         => 'login',
                    'email'         => 'email',
                    'password'      => 'password',
                    'floor_id'      => 2,
                    'genesis_id'    => 3,
                    'profession_id' => 3,
                    'avatar_id'     => 21,
                    'ref'           => 'referral',
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_USER_AGENT,
            ],
            // user_agent invalid type
            [
                [
                    'login'         => 'login',
                    'email'         => 'email',
                    'password'      => 'password',
                    'floor_id'      => 2,
                    'genesis_id'    => 3,
                    'profession_id' => 3,
                    'avatar_id'     => 21,
                    'ref'           => 'referral',
                    'user_agent'    => true,
                    'ip'            => '127.0.0.1',
                ],
                AccountException::INVALID_USER_AGENT,
            ],

            // miss ip
            [
                [
                    'login'         => 'login',
                    'email'         => 'email',
                    'password'      => 'password',
                    'floor_id'      => 2,
                    'genesis_id'    => 3,
                    'profession_id' => 3,
                    'avatar_id'     => 21,
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                ],
                AccountException::INVALID_IP,
            ],
            // ip invalid type
            [
                [
                    'login'         => 'login',
                    'email'         => 'email',
                    'password'      => 'password',
                    'floor_id'      => 2,
                    'genesis_id'    => 3,
                    'profession_id' => 3,
                    'avatar_id'     => 21,
                    'ref'           => 'referral',
                    'user_agent'    => 'user info',
                    'ip'            => 12.32,
                ],
                AccountException::INVALID_IP,
            ],
        ];
    }
}
