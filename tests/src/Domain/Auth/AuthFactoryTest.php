<?php

declare(strict_types=1);

namespace Test\src\Domain\Auth;

use App\Domain\Account\AccountInterface;
use App\Domain\Account\Energy\EnergyFactory;
use App\Domain\Account\Group\AccountGroup;
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
     * @param int $expectedMaxUpload
     * @throws Exception
     */
    public function testAuthFactoryCreateSuccess(array $data, int $expectedMaxUpload): void
    {
        $auth = AuthFactory::create($data, $this->getSendNoticeAction());

        self::assertEquals($data['id'], $auth->getId());
        self::assertEquals($data['name'], $auth->getName());
        self::assertEquals($data['avatar'], $auth->getAvatar());
        self::assertEquals($data['verified_token'], $auth->getVerifiedToken());
        self::assertEquals($data['main_character_id'], $auth->getMainCharacterId());
        self::assertEquals(new AccountGroup($data['account_group_id']), $auth->getGroup());
        self::assertEquals(new AccountStatus($data['account_status_id']), $auth->getStatus());
        self::assertEquals($data['can_like'], $auth->isCanLike());
        self::assertEquals($data['level']['character_level'], $auth->getLevel()->getLevel());
        self::assertEquals($data['level']['character_stat_points'], $auth->getLevel()->getStatPoints());
        self::assertEquals($data['template'], $auth->getTemplate());
        self::assertEquals((bool)$data['email_verified'], $auth->isEmailVerified());
        self::assertEquals($data['upload'], $auth->getUpload()->getUpload());
        self::assertEquals($expectedMaxUpload, $auth->getUpload()->getUploadMax());

        $exceptedEnergy = EnergyFactory::create($data['energy']);

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
        AuthFactory::create($data, $this->getSendNoticeAction());
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
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                24117248,
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
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_ID,
            ],
            [
                // id некорректного типа
                [
                    'id'                     => 123,
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_ID,
            ],
            [
                // отсутствует name
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_NAME,
            ],
            [
                // name некорректного типа
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => true,
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_NAME,
            ],
            [
                // отсутствует avatar
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_AVATAR,
            ],
            [
                // avatar некорректного типа
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => ['account_avatar.png'],
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_AVATAR,
            ],
            [
                // отсутствует account_group_id
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_ACCOUNT_GROUP_ID,
            ],
            [
                // account_group_id некорректного типа
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'can_like'               => 1,
                    'notices'                => [],
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_ACCOUNT_GROUP_ID,
            ],
            [
                // отсутствует account_status_id
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
                    'account_group_id'       => 10,
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_ACCOUNT_STATUS_ID,
            ],
            [
                // account_status_id некорректного типа
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'can_like'               => 1,
                    'notices'                => [],
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_ACCOUNT_STATUS_ID,
            ],
            [
                // отсутствует energy
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'can_like'               => 1,
                    'notices'                => [],
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_ENERGY_DATA,
            ],
            [
                // energy некорректного типа
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
                    'account_group_id'       => 10,
                    'account_status_id'      => 1,
                    'energy'                 => 100,
                    'can_like'               => 1,
                    'notices'                => [],
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_ENERGY_DATA,
            ],
            [
                // отсутствует can_like
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_CAN_LIKE,
            ],
            [
                // can_like некорректного типа
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'can_like'               => null,
                    'notices'                => [],
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_CAN_LIKE,
            ],

            [
                // отсутствует notices
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_NOTICES_DATA,
            ],

            [
                // notices некорректного типа
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'notices'                => 'notices',
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_NOTICES_DATA,
            ],

            [
                // notices содержит не-массивы
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'notices'                => [
                        'notice-1',
                        'notice-2',
                    ],
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_NOTICE_DATA,
            ],

            [
                // отсутствует level
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_LEVEL,
            ],

            [
                // level некорректного типа
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => '12',
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_LEVEL,
            ],

            [
                // miss template
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_TEMPLATE,
            ],
            [
                // template invalid type
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 123,
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_TEMPLATE,
            ],

            [
                // miss email_verified
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_EMAIL_VERIFIED,
            ],
            [
                // email_verified invalid type
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => false,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_EMAIL_VERIFIED,
            ],

            [
                // miss upload
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_UPLOAD,
            ],
            [
                // upload invalid type
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => '1000',
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_UPLOAD,
            ],
            [
                // upload over min value
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => AccountInterface::UPLOAD_MIN_VALUE - 1,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_UPLOAD_VALUE . AccountInterface::UPLOAD_MIN_VALUE . '-' . AccountInterface::UPLOAD_MAX_VALUE,
            ],
            [
                // upload over max value
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => AccountInterface::UPLOAD_MAX_VALUE + 1,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_UPLOAD_VALUE . AccountInterface::UPLOAD_MIN_VALUE . '-' . AccountInterface::UPLOAD_MAX_VALUE,
            ],

            [
                // miss upload_bonus
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                ],
                AuthException::INVALID_UPLOAD_BONUS,
            ],
            [
                // upload_bonus invalid type
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 0,
                    'upload_bonus'           => null,
                ],
                AuthException::INVALID_UPLOAD_BONUS,
            ],
            // miss main_character_id
            [
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_MAIN_CHARACTER_ID,
            ],
            // main_character_id invalid type
            [
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => 111,
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_MAIN_CHARACTER_ID,
            ],
            // main_character_id invalid uuid
            [
                [
                    'id'                     => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'name'                   => 'AccountName',
                    'avatar'                 => 'account_avatar.png',
                    'verified_token'         => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                    'main_character_id'      => '2bccd10d-b2a3-4838-aca9-963582d529',
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
                    'level'                  => [
                        'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                        'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                        'character_level'       => 1,
                        'character_exp'         => 0,
                        'character_stat_points' => 0,
                    ],
                    'template'               => 'default',
                    'email_verified'         => 0,
                    'upload'                 => 1000,
                    'upload_bonus'           => 3,
                ],
                AuthException::INVALID_MAIN_CHARACTER_ID,
            ],
        ];
    }
}
