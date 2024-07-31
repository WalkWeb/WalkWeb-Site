<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Notice;

use App\Domain\Account\AccountException;
use App\Domain\Account\Notice\Notice;
use App\Domain\Account\Notice\NoticeException;
use App\Domain\Account\Notice\NoticeFactory;
use App\Domain\Account\Notice\NoticeRepository;
use DateTime;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class NoticeRepositoryTest extends AbstractTest
{
    /**
     * Test on success get Notice
     *
     * @throws AppException
     * @throws NoticeException
     */
    public function testNoticeRepositoryGetSuccess(): void
    {
        $id = 'd92bce7f-112d-442c-8a75-bf440f477af1';

        $notice = $this->getRepository()->get($id);

        self::assertEquals($id, $notice->getId());
        self::assertEquals(1, $notice->getTypeId());
        self::assertEquals(self::DEMO_USER, $notice->getAccountId());
        self::assertEquals('notice message 1', $notice->getMessage());
        self::assertFalse($notice->isView());
        self::assertEquals('2021-12-25 11:00:00', $notice->getCreatedAt()->format(self::DATE_FORMAT));
    }

    /**
     * Test on not found get Notice
     *
     * @throws AppException
     * @throws NoticeException
     */
    public function testNoticeRepositoryGetNotFound(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage(NoticeException::NOT_FOUND);
        $this->getRepository()->get('d92bce7f-112d-442c-8a75-bf440f477222');
    }

    /**
     * Test on success save Notice
     *
     * @throws AppException
     * @throws NoticeException
     */
    public function testNoticeRepositoryAddSuccess(): void
    {
        $accountId = self::DEMO_MODERATOR;
        $container = self::getContainer();
        $repository = new NoticeRepository($container);

        self::assertEquals(0, $container->getConnectionPool()->getConnection()->query(
            'SELECT `notice` FROM `accounts` WHERE `id` = ?',
            [['type' => 's', 'value' => $accountId]],
            true
        )['notice']);

        $notice = new Notice(
            'a38153cc-08ab-47a5-8ca0-89767e7aa1c5',
            2,
            $accountId,
            'save notice',
            true,
            new DateTime('2021-10-15 20:00:00'),
        );

        $this->getRepository()->add($notice);

        $noticeDb = $repository->get($notice->getId());

        self::assertEquals($notice->getId(), $noticeDb->getId());
        self::assertEquals($notice->getTypeId(), $noticeDb->getTypeId());
        self::assertEquals($notice->getAccountId(), $noticeDb->getAccountId());
        self::assertEquals($notice->getMessage(), $noticeDb->getMessage());
        self::assertEquals($notice->isView(), $noticeDb->isView());
        self::assertEquals(
            $notice->getCreatedAt()->format(self::DATE_FORMAT),
            $noticeDb->getCreatedAt()->format(self::DATE_FORMAT)
        );

        self::assertEquals(1, $container->getConnectionPool()->getConnection()->query(
            'SELECT `notice` FROM `accounts` WHERE `id` = ?',
            [['type' => 's', 'value' => $accountId]],
            true
        )['notice']);
    }

    /**
     * Test on fail save Notice at unknown account_id
     *
     * @throws AppException
     * @throws NoticeException
     */
    public function testNoticeRepositoryAddAccountIdNotFound(): void
    {
        $notice = new Notice(
            'f9d710c0-00e9-4509-99aa-11ba50895b6e',
            1,
            '5372b891-a3d8-460e-911e-574a1021532d',
            'save notice',
            true,
            new DateTime('2021-10-15 20:00:00'),
        );

        $this->expectException(AppException::class);
        $this->expectExceptionMessage(AccountException::NOT_FOUND);
        $this->getRepository()->add($notice);
    }

    /**
     * Test on get actual notices for account
     *
     * @dataProvider getActualDataProvider
     * @param string $accountId
     * @param array $noticesData
     * @param int $total
     * @throws AppException
     * @throws NoticeException
     */
    public function testNoticeRepositoryGetActual(string $accountId, array $noticesData, int $total): void
    {
        $notices = $this->getRepository()->getActual($accountId, 5);

        self::assertSameSize($noticesData, $notices);
        self::assertEquals($total, $notices->getTotal());

        $i = 0;
        foreach ($notices as $notice) {
            self::assertEquals(
                NoticeFactory::create($noticesData[$i]),
                $notice
            );
            $i++;
        }
    }

    /**
     * Test on get all notices for account
     *
     * @dataProvider getAllDataProvider
     * @param string $accountId
     * @param int $offset
     * @param int $limit
     * @param array $noticesData
     * @param int $total
     * @throws AppException
     * @throws NoticeException
     */
    public function testNoticeRepositoryGetAll(string $accountId, int $offset, int $limit, array $noticesData, int $total): void
    {
        $notices = $this->getRepository()->getAll($accountId, $offset, $limit);

        self::assertSameSize($noticesData, $notices);
        self::assertEquals($total, $notices->getTotal());

        $i = 0;
        foreach ($notices as $notice) {
            self::assertEquals(
                NoticeFactory::create($noticesData[$i]),
                $notice
            );
            $i++;
        }
    }

    /**
     * @return NoticeRepository
     * @throws AppException
     */
    private function getRepository(): NoticeRepository
    {
        return new NoticeRepository(self::getContainer());
    }

    /**
     * @return array
     */
    public function getActualDataProvider(): array
    {
        return [
            [
                self::DEMO_USER,
                [
                    [
                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477af1',
                        'type'       => 1,
                        'account_id' => self::DEMO_USER,
                        'message'    => 'notice message 1',
                        'view'       => 0,
                        'created_at' => '2021-12-25 11:00:00',
                    ],
                    [

                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477af2',
                        'type'       => 2,
                        'account_id' => self::DEMO_USER,
                        'message'    => 'notice message 2',
                        'view'       => 0,
                        'created_at' => '2021-12-25 12:00:00',
                    ],
                    [

                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477af3',
                        'type'       => 3,
                        'account_id' => self::DEMO_USER,
                        'message'    => 'notice message 3',
                        'view'       => 0,
                        'created_at' => '2021-12-25 13:00:00',
                    ],
                ],
                3,
            ],
            [
                'a29393e0-34b4-4419-9c13-f4e8a1b54cf2',
                [],
                0,
            ],
            [
                self::GAME_USER,
                [
                    [

                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477a01',
                        'type'       => 1,
                        'account_id' => self::GAME_USER,
                        'message'    => 'notice message 1',
                        'view'       => 0,
                        'created_at' => '2021-12-25 07:00:00',
                    ],
                    [

                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477a02',
                        'type'       => 3,
                        'account_id' => self::GAME_USER,
                        'message'    => 'notice message 2',
                        'view'       => 0,
                        'created_at' => '2021-12-25 08:00:00',
                    ],
                    [

                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477a03',
                        'type'       => 2,
                        'account_id' => self::GAME_USER,
                        'message'    => 'notice message 3',
                        'view'       => 0,
                        'created_at' => '2021-12-25 09:00:00',
                    ],
                    [

                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477a04',
                        'type'       => 1,
                        'account_id' => self::GAME_USER,
                        'message'    => 'notice message 4',
                        'view'       => 0,
                        'created_at' => '2021-12-25 10:00:00',
                    ],
                    [

                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477a05',
                        'type'       => 1,
                        'account_id' => self::GAME_USER,
                        'message'    => 'notice message 5',
                        'view'       => 0,
                        'created_at' => '2021-12-25 11:00:00',
                    ],
                ],
                9,
            ],
        ];
    }

    /**
     * @return array
     */
    public function getAllDataProvider(): array
    {
        return [
            // get all
            [
                self::DEMO_USER,
                0,
                10,
                [
                    [

                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477af5',
                        'type'       => 1,
                        'account_id' => self::DEMO_USER,
                        'message'    => 'notice message 5',
                        'view'       => 1,
                        'created_at' => '2021-12-25 15:00:00',
                    ],
                    [

                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477af4',
                        'type'       => 1,
                        'account_id' => self::DEMO_USER,
                        'message'    => 'notice message 4',
                        'view'       => 1,
                        'created_at' => '2021-12-25 14:00:00',
                    ],
                    [

                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477af3',
                        'type'       => 3,
                        'account_id' => self::DEMO_USER,
                        'message'    => 'notice message 3',
                        'view'       => 0,
                        'created_at' => '2021-12-25 13:00:00',
                    ],
                    [

                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477af2',
                        'type'       => 2,
                        'account_id' => self::DEMO_USER,
                        'message'    => 'notice message 2',
                        'view'       => 0,
                        'created_at' => '2021-12-25 12:00:00',
                    ],
                    [
                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477af1',
                        'type'       => 1,
                        'account_id' => self::DEMO_USER,
                        'message'    => 'notice message 1',
                        'view'       => 0,
                        'created_at' => '2021-12-25 11:00:00',
                    ],
                ],
                5,
            ],
            // check limit
            [
                self::DEMO_USER,
                0,
                2,
                [
                    [

                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477af5',
                        'type'       => 1,
                        'account_id' => self::DEMO_USER,
                        'message'    => 'notice message 5',
                        'view'       => 1,
                        'created_at' => '2021-12-25 15:00:00',
                    ],
                    [

                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477af4',
                        'type'       => 1,
                        'account_id' => self::DEMO_USER,
                        'message'    => 'notice message 4',
                        'view'       => 1,
                        'created_at' => '2021-12-25 14:00:00',
                    ],
                ],
                5,
            ],
            // check offset
            [
                self::DEMO_USER,
                2,
                10,
                [
                    [

                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477af3',
                        'type'       => 3,
                        'account_id' => self::DEMO_USER,
                        'message'    => 'notice message 3',
                        'view'       => 0,
                        'created_at' => '2021-12-25 13:00:00',
                    ],
                    [

                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477af2',
                        'type'       => 2,
                        'account_id' => self::DEMO_USER,
                        'message'    => 'notice message 2',
                        'view'       => 0,
                        'created_at' => '2021-12-25 12:00:00',
                    ],
                    [
                        'id'         => 'd92bce7f-112d-442c-8a75-bf440f477af1',
                        'type'       => 1,
                        'account_id' => self::DEMO_USER,
                        'message'    => 'notice message 1',
                        'view'       => 0,
                        'created_at' => '2021-12-25 11:00:00',
                    ],
                ],
                5,
            ],
            // nothing
            [
                'a29393e0-34b4-4419-9c13-f4e8a1b54cf2',
                0,
                10,
                [],
                0,
            ],
        ];
    }
}
