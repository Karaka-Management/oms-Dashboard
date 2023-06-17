<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\Dashboard\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Dashboard\Models;

/**
 * DashboardBoard class.
 *
 * @package Modules\Dashboard\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class DashboardComponent implements \JsonSerializable
{
    /**
     * ID.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Order.
     *
     * @var int
     * @since 1.0.0
     */
    public int $order = 0;

    /**
     * Board.
     *
     * @var int|DashboardBoard
     * @since 1.0.0
     */
    public int | DashboardBoard $board = 0;

    /**
     * Module.
     *
     * @var string
     * @since 1.0.0
     */
    public string $module = '';

    /**
     * Component.
     *
     * @var string
     * @since 1.0.0
     */
    public string $component = '';

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'        => $this->id,
            'board'     => $this->board,
            'order'     => $this->order,
            'module'    => $this->module,
            'component' => $this->component,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }
}
