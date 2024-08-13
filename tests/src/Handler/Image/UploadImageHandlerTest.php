<?php

declare(strict_types=1);

namespace Test\src\Handler\Image;

use App\Domain\Account\AccountInterface;
use App\Handler\Image\UploadImageHandler;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class UploadImageHandlerTest extends AbstractTest
{
    /**
     * @throws AppException
     */
    public function testUploadImageHandlerSuccess(): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request([
            'REQUEST_URI' => '/image/upload/json', 'REQUEST_METHOD' => 'POST'],
            [], [AccountInterface::AUTH_TOKEN => $token], [], [
                'file' => [
                    'name'     => 'ImageName',
                    'type'     => 'image/png',
                    'tmp_name' => __DIR__ . '/files/image.png',
                    'error'    => 0,
                    'size'     => 37308,
                ],
            ]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());

        $data = self::jsonDecode($response->getBody());

        self::assertTrue($data['success']);
        self::assertFileExists(DIR . '/public' .  $data['file_path']);
        self::assertEquals(357, $data['width']);
        self::assertEquals(270, $data['height']);
        self::assertEquals(1048576, $data['upload']);
        self::assertEquals(20971520, $data['upload_max']);
    }

    /**
     * @throws AppException
     */
    public function testUploadImageHandlerNoAuth(): void
    {
        $request = new Request([
            'REQUEST_URI' => '/image/upload/json', 'REQUEST_METHOD' => 'POST'],
            [], [], [], [
                'file' => [
                    'name'     => 'ImageName',
                    'type'     => 'image/png',
                    'tmp_name' => __DIR__ . '/files/image.png',
                    'error'    => 0,
                    'size'     => 37308,
                ],
            ]
        );
        $response = $this->createApp()->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(UploadImageHandler::NO_AUTH, $response);
    }

    /**
     * @throws AppException
     */
    public function testUploadImageHandlerNoUploadSpace(): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a5';
        $request = new Request([
            'REQUEST_URI' => '/image/upload/json', 'REQUEST_METHOD' => 'POST'],
            [], [AccountInterface::AUTH_TOKEN => $token], [], [
                'file' => [
                    'name'     => 'ImageName',
                    'type'     => 'image/png',
                    'tmp_name' => __DIR__ . '/files/image.png',
                    'error'    => 0,
                    'size'     => 37308,
                ],
            ]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(UploadImageHandler::NO_UPLOAD_SPACE, $response);
    }

    /**
     * @throws AppException
     */
    public function testUploadImageHandlerErrorLoad(): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request([
            'REQUEST_URI' => '/image/upload/json', 'REQUEST_METHOD' => 'POST'],
            [], [AccountInterface::AUTH_TOKEN => $token], [], [
                'file' => [
                    'name'     => 'ImageName',
                    'type'     => 'image/png',
                    'tmp_name' => __DIR__ . '/files/xxx.png',
                    'error'    => 0,
                    'size'     => 37308,
                ],
            ]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError('Loaded file not found', $response);
    }
}
