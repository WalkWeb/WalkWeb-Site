<?php

declare(strict_types=1);

namespace Test\src\Domain\Auth;

use App\Domain\Account\Energy\EnergyFactory;
use App\Domain\Account\Group\AccountGroup;
use App\Domain\Account\MainCharacter\Level\LevelInterface;
use App\Domain\Account\Notice\NoticeFactory;
use App\Domain\Account\Status\AccountStatus;
use App\Domain\Auth\AuthException;
use App\Domain\Auth\AuthFactory;
use Exception;
use Test\AbstractTest;

class AuthFactoryTest extends AbstractTest
{
    /**
     * Тест на успешное создание объекта Auth на основе массива с параметрами
     *
     * @dataProvider successDataProvider
     * @param array $data
     * @throws Exception
     */
    public function testAuthFactoryCreateSuccess(array $data): void
    {
        $auth = AuthFactory::create($data);

        self::assertEquals($data['id'], $auth->getId());
        self::assertEquals($data['name'], $auth->getName());
        self::assertEquals($data['avatar'], $auth->getAvatar());
        self::assertEquals(new AccountGroup($data['account_group_id']), $auth->getGroup());
        self::assertEquals(new AccountStatus($data['account_status_id']), $auth->getStatus());
        self::assertEquals($data['can_like'], $auth->isCanLike());
        self::assertEquals($data['level'], $auth->getLevel());
        self::assertEquals($data['stat_points'], $auth->getStatPoints());

        $exceptedEnergy = EnergyFactory::createFromDB($data['energy']);

        self::assertEquals($exceptedEnergy->getId(), $auth->getEnergy()->getId());
        self::assertEquals($exceptedEnergy->getEnergy(), $auth->getEnergy()->getEnergy());
        self::assertEquals($exceptedEnergy->getMaxEnergy(), $auth->getEnergy()->getMaxEnergy());
        self::assertEquals($exceptedEnergy->getUpdatedAt(), $auth->getEnergy()->getUpdatedAt());
        self::assertEquals($exceptedEnergy->getResidue(), $auth->getEnergy()->getResidue());
        self::assertEquals($exceptedEnergy->getEnergyWeight(), $auth->getEnergy()->getEnergyWeight());
        self::assertEquals($exceptedEnergy->getRestoreWeight(), $auth->getEnergy()->getRestoreWeight());
        self::assertEquals($exceptedEnergy->isUpdated(), $auth->getEnergy()->isUpdated());

        self::assertSameSize($data['notices'], $auth->getNotices());

        $i = 0;
        foreach ($auth->getNotices() as $notice) {
            self::assertEquals(
                NoticeFactory::create($data['notices'][$i]),
                $notice
            );
            $i++;
        }
    }

    /**
     * Тесты на различные варианты невалидных данных
     *
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     * @throws Exception
     */
    public function testAuthFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($error);
        AuthFactory::create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [
                        [
                            'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf8ce',
                            'type'       => 1,
                            'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                            'message'    => 'message #1',
                            'view'       => 0,
                            'created_at' => '2019-08-12 14:00:00',
                        ],
                        [
                            'id'         => 'cede1b4e-787b-4f9a-b005-786599990f9c',
                            'type'       => 2,
                            'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                            'message'    => 'message #2',
                            'view'       => 0,
                            'created_at' => '2019-08-18 18:50:00',
                        ],
                    ],
                    'level'                  => 12,
                    'stat_points'            => 5,
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
            [
                // отсутствует id
                [
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [],
                    'level'                  => 12,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_ID,
            ],
            [
                // id некорректного типа
                [
                    'id'                     => 123,
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [],
                    'level'                  => 12,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_ID,
            ],
            [
                // отсутствует name
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [],
                    'level'                  => 12,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_NAME,
            ],
            [
                // name некорректного типа
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => true,
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [],
                    'level'                  => 12,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_NAME,
            ],
            [
                // отсутствует avatar
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [],
                    'level'                  => 12,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_AVATAR,
            ],
            [
                // avatar некорректного типа
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => ['account_avatar.png'],
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [],
                    'level'                  => 12,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_AVATAR,
            ],
            [
                // отсутствует account_group_id
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [],
                    'level'                  => 12,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_ACCOUNT_GROUP_ID,
            ],
            [
                // account_group_id некорректного типа
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 'success',
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [],
                    'level'                  => 12,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_ACCOUNT_GROUP_ID,
            ],
            [
                // отсутствует account_status_id
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [],
                    'level'                  => 12,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_ACCOUNT_STATUS_ID,
            ],
            [
                // account_status_id некорректного типа
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => '1',
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [],
                    'level'                  => 12,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_ACCOUNT_STATUS_ID,
            ],
            [
                // отсутствует energy
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'can_like'               => true,
                    'notices'                => [],
                    'level'                  => 12,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_ENERGY_DATA,
            ],
            [
                // energy некорректного типа
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => 100,
                    'can_like'               => true,
                    'notices'                => [],
                    'level'                  => 12,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_ENERGY_DATA,
            ],
            [
                // отсутствует can_like
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'notices'                => [],
                    'level'                  => 12,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_CAN_LIKE,
            ],
            [
                // can_like некорректного типа
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => 1,
                    'notices'                => [],
                    'level'                  => 12,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_CAN_LIKE,
            ],

            [
                // отсутствует notices
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'level'                  => 12,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_NOTICES_DATA,
            ],

            [
                // notices некорректного типа
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => 'notices',
                    'level'                  => 12,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_NOTICES_DATA,
            ],

            [
                // notices содержит не-массивы
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [
                        'notice-1',
                        'notice-2',
                    ],
                    'level'                  => 12,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_NOTICE_DATA,
            ],

            [
                // отсутствует level
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [],
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_LEVEL,
            ],

            [
                // level некорректного типа
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [],
                    'level'                  => '12',
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_LEVEL,
            ],

            [
                // level меньше минимального значения
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [],
                    'level'                  => LevelInterface::MIN_LEVEL - 1,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_LEVEL_VALUE . LevelInterface::MIN_LEVEL . '-' . LevelInterface::MAX_LEVEL,
            ],

            [
                // level больше максимального значения
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [],
                    'level'                  => LevelInterface::MAX_LEVEL + 1,
                    'stat_points'            => 5,
                ],
                AuthException::INVALID_LEVEL_VALUE . LevelInterface::MIN_LEVEL . '-' . LevelInterface::MAX_LEVEL,
            ],

            [
                // отсутствует stat_points
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [],
                    'level'                  => 5,
                ],
                AuthException::INVALID_STAT_POINTS,
            ],

            [
                // stat_points некорректного типа
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => [
                        'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                        'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'energy'            => 30,
                        'energy_bonus'      => 15,
                        'energy_updated_at' => 1566745426.0000,
                        'energy_residue'    => 10,
                    ],
                    'can_like'               => true,
                    'notices'                => [],
                    'level'                  => 5,
                    'stat_points'            => '5',
                ],
                AuthException::INVALID_STAT_POINTS,
            ],
        ];
    }
}
