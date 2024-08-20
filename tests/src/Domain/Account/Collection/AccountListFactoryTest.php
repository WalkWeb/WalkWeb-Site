<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Collection;

use App\Domain\Account\AccountException;
use App\Domain\Account\AccountInterface;
use App\Domain\Account\Collection\AccountListFactory;
use Exception;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class AccountListFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testAccountListFactorySuccess(array $data): void
    {
        $account = AccountListFactory::create($data);

        self::assertEquals($data['id'], $account->getId());
        self::assertEquals($data['avatar'], $account->getAvatar());
        self::assertEquals($data['name'], $account->getName());
        self::assertEquals($data['level'], $account->getLevel());
        self::assertEquals($data['exp'], $account->getExp());
        self::assertEquals($data['status_id'], $account->getStatus()->getId());
        self::assertEquals($data['group_id'], $account->getGroup()->getId());
        self::assertEquals($data['post_count'], $account->getPostCount());
        self::assertEquals($data['comment_count'], $account->getCommentCount());
        self::assertEquals($data['carma'], $account->getCarma());
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     */
    public function testAccountListFactoryFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        AccountListFactory::create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => 'name',
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
            ],
        ];
    }

    /**
     * @return array[]
     * @throws Exception
     */
    public function failDataProvider(): array
    {
        return [
            // miss id
            [
                [
                    'avatar'        => 'avatar.png',
                    'name'          => 'name',
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_ID,
            ],
            // id invalid type
            [
                [
                    'id'            => 123,
                    'avatar'        => 'avatar.png',
                    'name'          => 'name',
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_ID,
            ],

            // miss avatar
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'name'          => 'name',
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_AVATAR,
            ],
            // avatar invalid type
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => null,
                    'name'          => 'name',
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_AVATAR,
            ],

            // miss name
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_NAME,
            ],
            // name invalid type
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => true,
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_NAME,
            ],
            // name over min length
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => self::generateString(AccountInterface::NAME_MIN_LENGTH - 1),
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_NAME_LENGTH . AccountInterface::NAME_MIN_LENGTH . '-' . AccountInterface::NAME_MAX_LENGTH,
            ],
            // name over max length
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => self::generateString(AccountInterface::NAME_MAX_LENGTH + 1),
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_NAME_LENGTH . AccountInterface::NAME_MIN_LENGTH . '-' . AccountInterface::NAME_MAX_LENGTH,
            ],
            // miss level
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => 'name',
                    'exp'           => 275,
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_LEVEL,
            ],
            // level invalid type
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => 'name',
                    'level'         => [3],
                    'exp'           => 275,
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_LEVEL,
            ],
            // miss exp
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => 'name',
                    'level'         => 3,
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_EXP,
            ],
            // exp invalid type
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => 'name',
                    'level'         => 3,
                    'exp'           => '275',
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_EXP,
            ],
            // miss status_id
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => 'name',
                    'level'         => 3,
                    'exp'           => 275,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_STATUS_ID,
            ],
            // status_id invalid type
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => 'name',
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => null,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_STATUS_ID,
            ],
            // miss group_id
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => 'name',
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => 1,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_GROUP_ID,
            ],
            // group_id invalid type
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => 'name',
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => 1,
                    'group_id'      => '10',
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_GROUP_ID,
            ],
            // miss carma
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => 'name',
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => 1,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'group_id'      => 10,
                ],
                AccountException::INVALID_CARMA,
            ],
            // carma invalid type
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => 'name',
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'comment_count' => 7,
                    'carma'         => false,
                ],
                AccountException::INVALID_CARMA,
            ],
            // miss post_count
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => 'name',
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_POST_COUNT,
            ],
            // post_count invalid type
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => 'name',
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'post_count'    => null,
                    'comment_count' => 7,
                    'carma'         => 10,
                ],
                AccountException::INVALID_POST_COUNT,
            ],
            // miss comment_count
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => 'name',
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'carma'         => 10,
                ],
                AccountException::INVALID_COMMENT_COUNT,
            ],
            // comment_count invalid type
            [
                [
                    'id'            => 'ea5885e8-242b-4953-bb0f-7e2b86c318d3',
                    'avatar'        => 'avatar.png',
                    'name'          => 'name',
                    'level'         => 3,
                    'exp'           => 275,
                    'status_id'     => 1,
                    'group_id'      => 10,
                    'post_count'    => 4,
                    'comment_count' => true,
                    'carma'         => 10,
                ],
                AccountException::INVALID_COMMENT_COUNT,
            ],
        ];
    }
}
