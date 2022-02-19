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

/**
 * DashboardBoard class.
 *
 * @package Modules\Dashboard\Models
 * @license OMS License 1.0
 * @link    https://karaka.app
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
    protected int $id = 0;

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
     * Get id
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

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
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
