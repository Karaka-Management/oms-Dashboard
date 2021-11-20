<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */

declare(strict_types=1);

namespace Modules\Dashboard\tests\Models;

use Modules\Dashboard\Models\DashboardBoard;
use Modules\Dashboard\Models\DashboardBoardStatus;
use Modules\Dashboard\Models\DashboardComponent;
use Modules\Admin\Models\NullAccount;

/**
 * @internal
 */
final class DashboardBoardTest extends \PHPUnit\Framework\TestCase
{
    private DashboardBoard $board;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->board = new DashboardBoard();
    }

    /**
     * @covers Modules\Dashboard\Models\DashboardBoard
     * @group module
     */
    public function testDefault(): void
    {
        self::assertEquals(0, $this->board->getId());
        self::assertEquals('', $this->board->title);
        self::assertEquals([], $this->board->getComponents());
        self::assertEquals(DashboardBoardStatus::ACTIVE, $this->board->getStatus());
        self::assertInstanceOf('\Modules\Admin\Models\NullAccount', $this->board->account);
        self::assertInstanceOf('\Modules\Dashboard\Models\NullDashboardComponent', $this->board->getComponent(0));
    }

    /**
     * @covers Modules\Dashboard\Models\DashboardBoard
     * @group module
     */
    public function testStatusInputOutput(): void
    {
        $this->board->setStatus(DashboardBoardStatus::INACTIVE);
        self::assertEquals(DashboardBoardStatus::INACTIVE, $this->board->getStatus());
    }

    /**
     * @covers Modules\Dashboard\Models\DashboardBoard
     * @group module
     */
    public function testInvalidStatus(): void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);
        $this->board->setStatus(999);
    }

    /**
     * @covers Modules\Dashboard\Models\DashboardBoard
     * @group module
     */
    public function testComponentInputOutput(): void
    {
        $id = $this->board->addComponent($temp = new DashboardComponent());
        self::assertEquals($temp, $this->board->getComponent($id));

        self::assertTrue($this->board->removeComponent($id));
        self::assertFalse($this->board->removeComponent($id));
    }

    /**
     * @covers Modules\Dashboard\Models\DashboardBoard
     * @group module
     */
    public function testSerialize(): void
    {
        $this->board->title   = 'Title';
        $this->board->account = new NullAccount(2);
        $this->board->setStatus(DashboardBoardStatus::INACTIVE);

        self::assertEquals(
            [
                'id'          => 0,
                'account'     =>  $this->board->account,
                'title'       => 'Title',
                'status'      => DashboardBoardStatus::INACTIVE,
                'components'  => [],
            ],
            $this->board->jsonSerialize()
        );
    }
}
