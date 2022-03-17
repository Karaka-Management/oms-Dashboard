<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   Modules\Dashboard\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Dashboard\Models;

use phpOMS\Stdlib\Base\Enum;

/**
 * Permision state enum.
 *
 * @package Modules\Dashboard\Models
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
abstract class PermissionCategory extends Enum
{
    public const DASHBOARD = 1;

    public const BOARD = 2;

    public const COMPONENT = 3;
}
