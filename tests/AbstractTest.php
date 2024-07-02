<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use WalkWeb\NW\App;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Route\Router;
use WalkWeb\NW\Runtime;
use WalkWeb\NW\Traits\StringTrait;

abstract class AbstractTest extends TestCase
{
    public const DATE_FORMAT = 'Y-m-d H:i:s';

    use StringTrait;

    protected App $app;
    protected string $dir;

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
        $this->app = new App($router, $this->getContainer());
    }

    /**
     * @param Router $router
     * @return App
     * @throws AppException
     */
    protected function getApp(Router $router): App
    {
        return new App($router, $this->getContainer());
    }

    /**
     * @param string $appEnv
     * @param string $viewDir
     * @param string $migrationDir
     * @return Container
     * @throws AppException
     */
    protected function getContainer(
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
}
