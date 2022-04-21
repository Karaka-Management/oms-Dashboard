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
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Dashboard\Models;

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package Modules\Dashboard\Models
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class DashboardComponentMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'dashboard_component_id'        => ['name' => 'dashboard_component_id',        'type' => 'int',    'internal' => 'id'],
        'dashboard_component_order'     => ['name' => 'dashboard_component_order',     'type' => 'int',    'internal' => 'order'],
        'dashboard_component_module'    => ['name' => 'dashboard_component_module',    'type' => 'string', 'internal' => 'module'],
        'dashboard_component_component' => ['name' => 'dashboard_component_component', 'type' => 'string', 'internal' => 'component'],
        'dashboard_component_board'     => ['name' => 'dashboard_component_board',     'type' => 'int',    'internal' => 'board'],
    ];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:string, external:string, column?:string, by?:string}>
     * @since 1.0.0
     */
    public const BELONGS_TO = [
        'board' => [
            'mapper'     => DashboardBoardMapper::class,
            'external'   => 'dashboard_component_board',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'dashboard_component';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD ='dashboard_component_id';
}
