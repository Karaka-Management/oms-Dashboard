<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Dashboard
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

// @bug Drag&Drop element sometimes disappear on drop
//      For that reason drag&drop got removed temporarily
//      https://github.com/Karaka-Management/oms-Dashboard/issues/8
?>
<div class="row">
    <?php
        $panels = $this->data['panels'];
        if (empty($panels)) : ?>
    <div class="emptyPage"></div>
    <?php else:
        foreach ($panels as $panel) : ?>
        <?= $panel->render(); ?>
    <?php endforeach;
    endif; ?>
</div>