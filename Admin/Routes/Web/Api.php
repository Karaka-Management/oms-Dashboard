<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

use Modules\Dashboard\Controller\ApiController;
use Modules\Dashboard\Models\PermissionState;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/dashboard/board(\?.*|$)' => [
        [
            'dest'       => '\Modules\Dashboard\Controller\ApiController:apiBoardCreate',
            'verb'       => RouteVerb::PUT,
            'permission' => [
                'module' => ApiController::MODULE_NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionState::BOARD,
            ],
        ],
    ],
    '^.*/dashboard/board/component(\?.*|$)' => [
        [
            'dest'       => '\Modules\Dashboard\Controller\ApiController:apiComponentAdd',
            'verb'       => RouteVerb::PUT,
            'permission' => [
                'module' => ApiController::MODULE_NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionState::COMPONENT,
            ],
        ],
    ],
];
