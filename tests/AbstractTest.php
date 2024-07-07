<?php

declare(strict_types=1);

namespace Test;

use App\Domain\Account\Notice\Action\SendNoticeAction;
use App\Domain\Account\Notice\Action\SendNoticeActionInterface;
use App\Domain\Account\Notice\NoticeRepository;
use PHPUnit\Framework\TestCase;
use WalkWeb\NW\App;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Runtime;
use WalkWeb\NW\Traits\StringTrait;

abstract class AbstractTest extends TestCase
{
    public const DATE_FORMAT     = 'Y-m-d H:i:s';

    public const DEMO_USER       = '1e3a3b27-12da-4c73-a3a7-b83092705b01';
    public const BLOCKED_USER    = '1e3a3b27-12da-4c73-a3a7-b83092705b02';
    public const NO_END_REG_USER = '1e3a3b27-12da-4c73-a3a7-b83092705b03';
    public const DEMO_MODERATOR  = '1e3a3b27-12da-4c73-a3a7-b83092705b04';

    use StringTrait;

    protected App $app;
    protected string $dir;

    private static ?Container $container = null;

    /**
     * @throws AppException
     */
    public function setUp(): void
    {
        $this->dir = __DIR__;

        if (file_exists(__DIR__ . '/../config.test.php')) {
            require_once __DIR__ . '/../config.test.php';
        } else {
            require_once __DIR__ . '/../config.php';
        }

        $router = require __DIR__ . '/../routes/web.php';
        self::getContainer()->getConnectionPool()->getConnection()->autocommit(false);
        $this->app = new App($router, self::getContainer());
    }

    /**
     * @throws AppException
     */
    public function setDown(): void
    {
        self::getContainer()->getConnectionPool()->getConnection()->rollback();
    }

    /**
     * @return Container
     * @throws AppException
     */
    public static function getContainer(): Container
    {
        if (self::$container === null) {
            self::$container = self::createContainer();
        }

        return self::$container;
    }

    /**
     * @return App
     * @throws AppException
     */
    protected function createApp(): App
    {
        $router = require __DIR__ . '/../routes/web.php';
        return new App($router, self::createContainer());
    }

    /**
     * @param string $appEnv
     * @param string $viewDir
     * @param string $migrationDir
     * @return Container
     * @throws AppException
     */
    protected static function createContainer(
        string $appEnv = APP_ENV,
        string $viewDir = VIEW_DIR,
        string $migrationDir = MIGRATION_DIR
    ): Container
    {
        $container = new Container(
            $appEnv,
            DB_CONFIGS,
            MAIL_CONFIG,
            SAVE_LOG,
            LOG_DIR,
            LOG_FILE_NAME,
            CACHE_DIR,
            $viewDir,
            $migrationDir,
            TEMPLATE_DEFAULT,
        );
        $container->set(Runtime::class, new Runtime());

        return $container;
    }

    /**
     * @return SendNoticeActionInterface
     * @throws AppException
     */
    protected function getSendNoticeAction(): SendNoticeActionInterface
    {
        return new SendNoticeAction(new NoticeRepository(self::getContainer()));
    }
}
