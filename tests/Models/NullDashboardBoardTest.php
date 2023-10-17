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

use Modules\Dashboard\Models\NullDashboardBoard;

/**
 * @internal
 */
final class NullDashboardBoardTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Dashboard\Models\NullDashboardBoard
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\Dashboard\Models\DashboardBoard', new NullDashboardBoard());
    }

    /**
     * @covers Modules\Dashboard\Models\NullDashboardBoard
     * @group module
     */
    public function testId() : void
    {
        $null = new NullDashboardBoard(2);
        self::assertEquals(2, $null->id);
    }

    /**
     * @covers Modules\Dashboard\Models\NullDashboardBoard
     * @group module
     */
    public function testJsonSerialize() : void
    {
        $null = new NullDashboardBoard(2);
        self::assertEquals(['id' => 2], $null->jsonSerialize());
    }
}
