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

use Modules\Admin\Models\NullAccount;
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

/**
 * Api controller for the dashboard module.
 *
 * @package Modules\Dashboard
 * @license OMS License 2.0
 * @link    https://jingga.app
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
        if (($val['title'] = !$request->hasData('title'))) {
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
    public function apiBoardCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateBoardCreate($request))) {
            $response->data[$request->uri->__toString()] = new FormValidation($val);
            $response->header->status                    = RequestStatusCode::R_400;

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
        $board->title   = $request->getDataString('title') ?? '';
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
        if (($val['board'] = !$request->hasData('board'))
            || ($val['module'] = !$request->hasData('module'))
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
    public function apiComponentCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateComponentCreate($request))) {
            $response->data[$request->uri->__toString()] = new FormValidation($val);
            $response->header->status                    = RequestStatusCode::R_400;

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
        $component->board  = $request->getDataInt('board') ?? 0;
        $component->order  = $request->getDataInt('order') ?? 0;
        $component->module = $request->getDataString('module') ?? '';

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
    public function apiComponentAdd(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
    }
}
