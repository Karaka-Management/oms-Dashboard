<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Dashboard\Admin
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Dashboard\Admin;

use Modules\Admin\Models\NullAccount;
use Modules\Dashboard\Models\DashboardBoard;
use Modules\Dashboard\Models\DashboardBoardMapper;
use Modules\Dashboard\Models\DashboardBoardStatus;
use Modules\Dashboard\Models\DashboardComponent;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\Config\SettingsInterface;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Module\InstallerAbstract;
use phpOMS\Module\ModuleInfo;
use phpOMS\System\File\PathException;
use phpOMS\Uri\HttpUri;

/**
 * Installer class.
 *
 * @package Modules\Dashboard\Admin
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Installer extends InstallerAbstract
{
    /**
     * Path of the file
     *
     * @var string
     * @since 1.0.0
     */
    public const PATH = __DIR__;

    /**
     * {@inheritdoc}
     */
    public static function install(ApplicationAbstract $app, ModuleInfo $info, SettingsInterface $cfgHandler) : void
    {
        parent::install($app, $info, $cfgHandler);

        self::installDefault();
    }

    /**
     * Install default dashboard
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function installDefault() : void
    {
        $board          = new DashboardBoard();
        $board->title   = 'Default Board';
        $board->account = new NullAccount(1);
        $board->setStatus(DashboardBoardStatus::ACTIVE);

        DashboardBoardMapper::create()->execute($board);
    }

    /**
     * Install data from providing modules.
     *
     * The data can be either directories which should be created or files which should be "uploaded"
     *
     * @param ApplicationAbstract $app  Application
     * @param array               $data Additional data
     *
     * @return array
     *
     * @throws PathException
     * @throws \Exception
     *
     * @since 1.0.0
     */
    public static function installExternal(ApplicationAbstract $app, array $data) : array
    {
        if (!\is_file($data['path'] ?? '')) {
            throw new PathException($data['path'] ?? '');
        }

        $dashboardFile = \file_get_contents($data['path'] ?? '');
        if ($dashboardFile === false) {
            throw new PathException($data['path'] ?? ''); // @codeCoverageIgnore
        }

        $dashboardData = \json_decode($dashboardFile, true) ?? [];
        if ($dashboardData === false) {
            throw new \Exception(); // @codeCoverageIgnore
        }

        $result = [
            'component' => [],
        ];

        $apiApp = new class() extends ApplicationAbstract
        {
            protected string $appName = 'Api';
        };

        $apiApp->dbPool         = $app->dbPool;
        $apiApp->orgId          = $app->orgId;
        $apiApp->accountManager = $app->accountManager;
        $apiApp->appSettings    = $app->appSettings;
        $apiApp->moduleManager  = $app->moduleManager;
        $apiApp->eventManager   = $app->eventManager;

        /** @var array $dashboardData */
        foreach ($dashboardData as $dashboard) {
            switch ($dashboard['type']) {
                case 'component':
                    $result['component'][] = self::createComponent($apiApp, $dashboard);
                    break;
                default:
            }
        }

        return $result;
    }

    /**
     * Create board component.
     *
     * @param ApplicationAbstract $app  Application
     * @param array               $data Type info
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function createComponent(ApplicationAbstract $app, array $data) : array
    {
        $module = $app->moduleManager->get('Dashboard');

        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('board', (int) ($data['board'] ?? 0));
        $request->setData('order', (int) ($data['order'] ?? 0));
        $request->setData('module', (string) ($data['module'] ?? ''));

        $module->apiComponentCreate($request, $response);

        $responseData = $response->get('');
        if (!\is_array($responseData)) {
            return [];
        }

        return !\is_array($responseData['response'])
            ? $responseData['response']->toArray()
            : $responseData['response'];
    }
}
