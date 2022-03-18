<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   Modules\Dashboard\Admin
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Dashboard\Admin;

use Modules\Admin\Models\NullAccount;
use Modules\Dashboard\Models\DashboardBoard;
use Modules\Dashboard\Models\DashboardBoardMapper;
use Modules\Dashboard\Models\DashboardBoardStatus;
use Modules\Dashboard\Models\DashboardComponent;
use Modules\Dashboard\Models\DashboardComponentMapper;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\Config\SettingsInterface;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\Module\InstallerAbstract;
use phpOMS\Module\ModuleInfo;
use phpOMS\System\File\PathException;

/**
 * Installer class.
 *
 * @package Modules\Dashboard\Admin
 * @license OMS License 1.0
 * @link    https://karaka.app
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

        foreach ($dashboardData as $dashboard) {
            switch ($dashboard['type']) {
                case 'component':
                    $result['component'][] = self::createComponent($app->dbPool, $dashboard);
                    break;
                default:
            }
        }

        return $result;
    }

    /**
     * Create board component.
     *
     * @param DatabasePool $dbPool Database instance
     * @param array        $data   Type info
     *
     * @return EditorDocType
     *
     * @since 1.0.0
     */
    private static function createComponent(DatabasePool $dbPool, array $data) : DashboardComponent
    {
        $component         = new DashboardComponent();
        $component->board  = (int) ($data['board'] ?? 0);
        $component->order  = (int) ($data['order'] ?? 0);
        $component->module = (string) ($data['module'] ?? '');

        DashboardComponentMapper::create()->execute($component);

        return $component;
    }
}
