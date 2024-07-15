<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Upload;

use App\Domain\Account\Upload\AccountUpload;
use Test\AbstractTest;

class AccountUploadTest extends AbstractTest
{
    /**
     * Test on success created AccountUpload object
     *
     * @dataProvider successDataProvider
     * @param int $upload
     * @param int $uploadMax
     * @param int $uploadBarWeight
     */
    public function testAccountUploadCreateSuccess(int $upload, int $uploadMax, int $uploadBarWeight): void
    {
        $accountUpload = new AccountUpload($upload, $uploadMax);

        self::assertEquals($upload, $accountUpload->getUpload());
        self::assertEquals(round($upload / 1048576, 1), $accountUpload->getUploadMb());
        self::assertEquals($uploadMax, $accountUpload->getUploadMax());
        self::assertEquals(round($uploadMax / 1048576, 1), $accountUpload->getUploadMaxMb());
        self::assertEquals($uploadMax - $upload, $accountUpload->getUploadRemainder());
        self::assertEquals($uploadBarWeight, $accountUpload->getUploadBarWeight());
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                50,
                100,
                50,
            ],
            [
                0,
                10,
                0,
            ],
            [
                1000,
                1000,
                100,
            ],
        ];
    }
}
