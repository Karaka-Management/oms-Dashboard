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

use Modules\Dashboard\Models\NullDashboardBoard;

/**
 * @internal
 */
final class NullDashboardBoardTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Dashboard\Models\NullDashboardBoard
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\Dashboard\Models\DashboardBoard', new NullDashboardBoard());
    }

    /**
     * @covers Modules\Dashboard\Models\NullDashboardBoard
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullDashboardBoard(2);
        self::assertEquals(2, $null->getId());
    }
}
