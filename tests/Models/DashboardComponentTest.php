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

use Modules\Dashboard\Models\DashboardComponent;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\Dashboard\Models\DashboardComponent::class)]
final class DashboardComponentTest extends \PHPUnit\Framework\TestCase
{
    private DashboardComponent $component;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->component = new DashboardComponent();
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testDefault() : void
    {
        self::assertEquals(0, $this->component->id);
        self::assertEquals(0, $this->component->order);
        self::assertEquals(0, $this->component->board);
        self::assertEquals('', $this->component->module);
        self::assertEquals('', $this->component->component);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testSerialize() : void
    {
        $this->component->board     = 3;
        $this->component->order     = 2;
        $this->component->module    = 'Test';
        $this->component->component = 'Component';

        self::assertEquals(
            [
                'id'        => 0,
                'board'     => 3,
                'order'     => 2,
                'module'    => 'Test',
                'component' => 'Component',
            ],
            $this->component->jsonSerialize()
        );
    }
}
