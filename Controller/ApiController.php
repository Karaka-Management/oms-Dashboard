<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   Modules\Dashboard
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Dashboard\Controller;

use Modules\Dashboard\Models\DashboardBoard;
use Modules\Dashboard\Models\DashboardBoardMapper;
use Modules\Dashboard\Models\DashboardBoardStatus;
use Modules\Dashboard\Models\DashboardComponent;
use Modules\Dashboard\Models\DashboardComponentMapper;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Model\Message\FormValidation;
use Modules\Admin\Models\NullAccount;

/**
 * Api controller for the dashboard module.
 *
 * @package Modules\Dashboard
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class ApiController extends Controller
{
    /**
     * Validate board create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateBoardCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = empty($request->getData('title')))) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create a board
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiBoardCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        if (!empty($val = $this->validateBoardCreate($request))) {
            $response->set($request->uri->__toString(), new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $board = $this->createBoardFromRequest($request);
        $this->createModel($request->header->account, $board, DashboardBoardMapper::class, 'board', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Board', 'Board successfully created.', $board);
    }

    /**
     * Method to create board from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return DashboardBoard Returns the created board from the request
     *
     * @since 1.0.0
     */
    private function createBoardFromRequest(RequestAbstract $request) : DashboardBoard
    {
        $board          = new DashboardBoard();
        $board->title   = (string) ($request->getData('title') ?? '');
        $board->account = new NullAccount($request->header->account);
        $board->setStatus(DashboardBoardStatus::ACTIVE);

        return $board;
    }

    /**
     * Validate component create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateComponentCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['board'] = empty($request->getData('board')))
            || ($val['module'] = empty($request->getData('module')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create a component
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiComponentCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        if (!empty($val = $this->validateComponentCreate($request))) {
            $response->set($request->uri->__toString(), new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $component = $this->createComponentFromRequest($request);
        $this->createModel($request->header->account, $component, DashboardComponentMapper::class, 'component', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Component', 'Component successfully created.', $component);
    }

    /**
     * Method to create board from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return DashboardComponent Returns the created board from the request
     *
     * @since 1.0.0
     */
    private function createComponentFromRequest(RequestAbstract $request) : DashboardComponent
    {
        $component         = new DashboardComponent();
        $component->board  = (int) ($request->getData('board') ?? 0);
        $component->order  = (int) ($request->getData('order') ?? 0);
        $component->module = (string) ($request->getData('module') ?? '');

        return $component;
    }

    /**
     * Api method to create a board
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiComponentAdd(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
    }
}
