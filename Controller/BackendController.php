<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\Dashboard
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Dashboard\Controller;

use Modules\Dashboard\Models\DashboardBoardMapper;
use Modules\Dashboard\Models\DashboardElementInterface;
use phpOMS\Contract\RenderableInterface;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

/**
 * Dashboard class.
 *
 * @package Modules\Dashboard
 * @license OMS License 2.0
 * @link    https://jingga.app
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
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewDashboard(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Dashboard/Theme/Backend/dashboard');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1000301001, $request, $response);

        /** @var \Modules\Dashboard\Models\DashboardBoard $board */
        $board = DashboardBoardMapper::get()
            ->with('components')
            ->where('account', $request->header->account)
            ->limit(1)
            ->execute();

        if ($board->id === 0) {
            /** @var \Modules\Dashboard\Models\DashboardBoard $board */
            $board = DashboardBoardMapper::get()->where('id', 1)->execute();
        }

        $panels          = [];
        $boardComponents = $board->getComponents();

        foreach ($boardComponents as $component) {
            if (!$this->app->moduleManager->isActive($component->module)) {
                continue;
            }

            $module = $this->app->moduleManager->get($component->module);
            if ($module instanceof DashboardElementInterface) {
                $panels[] = $module->viewDashboard($request, $response, $data);
            }
        }

        $view->data['panels'] = $panels;

        return $view;
    }
}
