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

use Modules\Dashboard\Models\NullDashboardComponent;

/**
 * @internal
 */
final class NullDashboardComponentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Dashboard\Models\NullDashboardComponent
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\Dashboard\Models\DashboardComponent', new NullDashboardComponent());
    }

    /**
     * @covers Modules\Dashboard\Models\NullDashboardComponent
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullDashboardComponent(2);
        self::assertEquals(2, $null->getId());
    }
}