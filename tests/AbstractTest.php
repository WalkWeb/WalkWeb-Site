<?php

declare(strict_types=1);

namespace Test;

use App\Domain\Account\Character\Avatar\Avatar;
use App\Domain\Account\Character\Avatar\AvatarInterface;
use App\Domain\Account\Character\Genesis\Genesis;
use App\Domain\Account\Floor\Floor;
use App\Domain\Account\Notice\Action\SendNoticeAction;
use App\Domain\Account\Notice\Action\SendNoticeActionInterface;
use App\Domain\Account\Notice\NoticeCollection;
use App\Domain\Account\Notice\NoticeRepository;
use App\Domain\Auth\AuthFactory;
use App\Domain\Auth\AuthInterface;
use App\Domain\Theme\Theme;
use Exception;
use PHPUnit\Framework\TestCase;
use WalkWeb\NW\App;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Response;
use WalkWeb\NW\Runtime;
use WalkWeb\NW\Traits\StringTrait;

abstract class AbstractTest extends TestCase
{
    public const DATE_FORMAT     = 'Y-m-d H:i:s';

    public const DEMO_USER       = '1e3a3b27-12da-4c73-a3a7-b83092705b01';
    public const BLOCKED_USER    = '1e3a3b27-12da-4c73-a3a7-b83092705b02';
    public const NO_END_REG_USER = '1e3a3b27-12da-4c73-a3a7-b83092705b03';
    public const DEMO_MODERATOR  = '1e3a3b27-12da-4c73-a3a7-b83092705b04';
    public const DEMO_CHAT_ADMIN = '1e3a3b27-12da-4c73-a3a7-b83092705b10';
    public const GAME_USER       = '1e3a3b27-12da-4c73-a3a7-b83092705b11';

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
        self::getContainer()->unset('user');
        $this->app = new App($router, self::getContainer());
    }

    /**
     * @throws AppException
     */
    public function tearDown(): void
    {
        self::getContainer()->getConnectionPool()->getConnection()->rollback();
    }

    /**
     * @return array
     */
    public function templateDataProvider(): array
    {
        return [
            [
                'default'
            ],
            [
                'inferno'
            ],
        ];
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
     * @param string $template
     * @return App
     * @throws AppException
     */
    protected function createApp(string $template = TEMPLATE_DEFAULT): App
    {
        $router = require __DIR__ . '/../routes/web.php';
        return new App($router, self::createContainer(APP_ENV, VIEW_DIR, MIGRATION_DIR, $template));
    }

    /**
     * @param string $appEnv
     * @param string $viewDir
     * @param string $migrationDir
     * @param string $template
     * @return Container
     * @throws AppException
     */
    protected static function createContainer(
        string $appEnv = APP_ENV,
        string $viewDir = VIEW_DIR,
        string $migrationDir = MIGRATION_DIR,
        string $template = TEMPLATE_DEFAULT
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
            $template,
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

    /**
     * @return AvatarInterface
     * @throws AppException
     */
    protected function getAvatar(): AvatarInterface
    {
        return new Avatar(
            115,
            new Genesis(1, new Theme(1), 'icon', 'plural', 'single'),
            new Floor(1),
            'origin.png',
            'small.png'
        );
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    protected static function jsonEncode(array $data): string
    {
        try {
            return json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            throw new AppException($e->getMessage());
        }
    }

    /**
     * @param string $json
     * @return array
     * @throws AppException
     */
    protected static function jsonDecode(string $json): array
    {
        try {
            return (array)json_decode($json, false, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            throw new AppException($e->getMessage());
        }
    }

    /**
     * @param string $error
     * @param Response $response
     * @throws AppException
     */
    protected static function assertJsonError(string $error, Response $response): void
    {
        self::assertEquals(self::jsonEncode(['success' => false, 'error' => $error]), $response->getBody());
    }

    /**
     * @return AuthInterface
     * @throws AppException
     */
    protected function createUser(): AuthInterface
    {
        return AuthFactory::create(
            [
                'id'                => '68435c80-eb31-4756-a260-a00900e5db9f',
                'name'              => 'AccountName',
                'avatar'            => 'account_avatar.png',
                'verified_token'    => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3',
                'main_character_id' => 'b1d4eccd-8b91-41c2-87b0-4538b76500af',
                'account_group_id'  => 10,
                'account_status_id' => 1,
                'energy'            => [
                    'energy_id'         => 'f0c4391a-f16a-4a22-80fb-ac0a02168b1f',
                    'account_id'        => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'energy'            => 30,
                    'energy_bonus'      => 15,
                    'energy_updated_at' => 1566745426.0000,
                    'energy_residue'    => 10,
                ],
                'can_like'          => 1,
                'level'             => [
                    'account_id'            => '68435c80-eb31-4756-a260-a00900e5db9f',
                    'character_id'          => '4a45c2f9-c46e-4dbb-bfaf-08494110d7e0',
                    'character_level'       => 1,
                    'character_exp'         => 0,
                    'character_stat_points' => 0,
                ],
                'template'          => 'default',
                'email_verified'    => 0,
                'upload'            => 1000,
                'upload_bonus'      => 3,
            ],
            $this->getSendNoticeAction(),
            new NoticeCollection(),
        );
    }
}
