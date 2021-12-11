<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\Dashboard
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Dashboard\Controller;

use Modules\Dashboard\Models\DashboardBoardMapper;
use Modules\Dashboard\Models\DashboardElementInterface;
use Modules\Dashboard\Models\NullDashboardBoard;
use phpOMS\Contract\RenderableInterface;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

/**
 * Dashboard class.
 *
 * @package Modules\Dashboard
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 * @codeCoverageIgnore
 */
final class BackendController extends Controller
{
    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewDashboard(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Dashboard/Theme/Backend/dashboard');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1000301001, $request, $response));

        $board = DashboardBoardMapper::get()->where('account', $request->header->account)->execute();

        if ($board instanceof NullDashboardBoard) {
            $board = DashboardBoardMapper::get()->where('id', 1)->execute();
        }

        $panels          = [];
        $boardComponents = $board->getComponents();

        foreach ($boardComponents as $component) {
            if (!$this->app->moduleManager->isActive($component->getModule())) {
                continue;
            }

            $module = $this->app->moduleManager->get($component->getModule());

            if (!($module instanceof DashboardElementInterface)) {
                continue;
            }

            $panels[] = $module->viewDashboard($request, $response, $data);
        }

        $view->addData('panels', $panels);

        return $view;
    }
}
