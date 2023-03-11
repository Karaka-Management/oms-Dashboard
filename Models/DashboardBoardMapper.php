<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Dashboard\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Dashboard\Models;

use Modules\Admin\Models\AccountMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package Modules\Dashboard\Models
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class DashboardBoardMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'dashboard_board_id'      => ['name' => 'dashboard_board_id',      'type' => 'int',    'internal' => 'id'],
        'dashboard_board_title'   => ['name' => 'dashboard_board_title',   'type' => 'string', 'internal' => 'title'],
        'dashboard_board_status'  => ['name' => 'dashboard_board_status',  'type' => 'int',    'internal' => 'status'],
        'dashboard_board_account' => ['name' => 'dashboard_board_account', 'type' => 'int',    'internal' => 'account'],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'components' => [
            'mapper'       => DashboardComponentMapper::class,
            'table'        => 'dashboard_component',
            'self'         => 'dashboard_component_board',
            'external'     => null,
        ],
    ];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:class-string, external:string, column?:string, by?:string}>
     * @since 1.0.0
     */
    public const BELONGS_TO = [
        'account' => [
            'mapper'     => AccountMapper::class,
            'external'   => 'dashboard_board_account',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'dashboard_board';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'dashboard_board_id';
}
