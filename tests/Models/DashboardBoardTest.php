<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Dashboard\tests\Models;

use Modules\Admin\Models\NullAccount;
use Modules\Dashboard\Models\DashboardBoard;
use Modules\Dashboard\Models\DashboardBoardStatus;
use Modules\Dashboard\Models\DashboardComponent;

/**
 * @internal
 */
final class DashboardBoardTest extends \PHPUnit\Framework\TestCase
{
    private DashboardBoard $board;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->board = new DashboardBoard();
    }

    /**
     * @covers \Modules\Dashboard\Models\DashboardBoard
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->board->id);
        self::assertEquals('', $this->board->title);
        self::assertEquals([], $this->board->getComponents());
        self::assertEquals(DashboardBoardStatus::ACTIVE, $this->board->status);
        self::assertInstanceOf('\Modules\Admin\Models\NullAccount', $this->board->account);
        self::assertInstanceOf('\Modules\Dashboard\Models\NullDashboardComponent', $this->board->getComponent(0));
    }

    /**
     * @covers \Modules\Dashboard\Models\DashboardBoard
     * @group module
     */
    public function testComponentInputOutput() : void
    {
        $id = $this->board->addComponent($temp = new DashboardComponent());
        self::assertEquals($temp, $this->board->getComponent($id));

        self::assertTrue($this->board->removeComponent($id));
        self::assertFalse($this->board->removeComponent($id));
    }

    /**
     * @covers \Modules\Dashboard\Models\DashboardBoard
     * @group module
     */
    public function testSerialize() : void
    {
        $this->board->title   = 'Title';
        $this->board->account = new NullAccount(2);
        $this->board->status  = DashboardBoardStatus::INACTIVE;

        self::assertEquals(
            [
                'id'         => 0,
                'account'    => $this->board->account,
                'title'      => 'Title',
                'status'     => DashboardBoardStatus::INACTIVE,
                'components' => [],
            ],
            $this->board->jsonSerialize()
        );
    }
}
