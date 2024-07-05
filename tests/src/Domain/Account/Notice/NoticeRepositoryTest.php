<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Notice;

use App\Domain\Account\AccountException;
use App\Domain\Account\Notice\Notice;
use App\Domain\Account\Notice\NoticeException;
use App\Domain\Account\Notice\NoticeRepository;
use App\Domain\Account\Notice\NoticeRepositoryInterface;
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
        self::assertEquals(1, $notice->getType());
        self::assertEquals('1e3a3b27-12da-4c73-a3a7-b83092705bae', $notice->getAccountId());
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
    public function testNoticeRepositorySaveSuccess(): void
    {
        $notice = new Notice(
            'a38153cc-08ab-47a5-8ca0-89767e7aa1c5',
            2,
            '22493866-7471-4333-b01f-d8eb65b26035',
            'save notice',
            true,
            new DateTime('2021-10-15 20:00:00'),
        );

        $this->getRepository()->save($notice);

        $noticeDb = $this->getRepository()->get($notice->getId());

        self::assertEquals($notice->getId(), $noticeDb->getId());
        self::assertEquals($notice->getType(), $noticeDb->getType());
        self::assertEquals($notice->getAccountId(), $noticeDb->getAccountId());
        self::assertEquals($notice->getMessage(), $noticeDb->getMessage());
        self::assertEquals($notice->isView(), $noticeDb->isView());
        self::assertEquals(
            $notice->getCreatedAt()->format(self::DATE_FORMAT),
            $noticeDb->getCreatedAt()->format(self::DATE_FORMAT)
        );
    }

    /**
     * Test on fail save Notice at unknown account_id
     *
     * @throws AppException
     * @throws NoticeException
     */
    public function testNoticeRepositorySaveAccountIdNotFound(): void
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
        $this->getRepository()->save($notice);
    }
    /**
     * @return NoticeRepositoryInterface
     * @throws AppException
     */
    private function getRepository(): NoticeRepositoryInterface
    {
        return new NoticeRepository(self::getContainer());
    }
}
