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

use Modules\Admin\Models\Account;
use Modules\Admin\Models\NullAccount;

/**
 * DashboardBoard class.
 *
 * @package Modules\Dashboard\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class DashboardBoard implements \JsonSerializable
{
    /**
     * ID.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Title.
     *
     * @var string
     * @since 1.0.0
     */
    public string $title = '';

    /**
     * Account.
     *
     * @var Account
     * @since 1.0.0
     */
    public Account $account;

    /**
     * Status.
     *
     * @var int
     * @since 1.0.0
     */
    public int $status = DashboardBoardStatus::ACTIVE;

    /**
     * Dashboard component.
     *
     * @var DashboardComponent[]
     * @since 1.0.0
     */
    public array $components = [];

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->account = new NullAccount();
    }

    /**
     * Adding board component.
     *
     * @param DashboardComponent $element Task element
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function addComponent(DashboardComponent $element) : int
    {
        $this->components[] = $element;

        \end($this->components);
        $key = (int) \key($this->components);
        \reset($this->components);

        return $key;
    }

    /**
     * Remove component from list.
     *
     * @param int $id Board component
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function removeComponent($id) : bool
    {
        if (isset($this->components[$id])) {
            unset($this->components[$id]);

            return true;
        }

        return false;
    }

    /**
     * Get board components.
     *
     * @return DashboardComponent[]
     *
     * @since 1.0.0
     */
    public function getComponents() : array
    {
        return $this->components;
    }

    /**
     * Get board component.
     *
     * @param int $id Component id
     *
     * @return DashboardComponent
     *
     * @since 1.0.0
     */
    public function getComponent(int $id) : DashboardComponent
    {
        return $this->components[$id] ?? new NullDashboardComponent();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'         => $this->id,
            'account'    => $this->account,
            'title'      => $this->title,
            'status'     => $this->status,
            'components' => $this->components,
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
