<div class="row">
    <?php
        $panels = $this->getData('panels');
        if (empty($panels)) : ?>
    <div class="emptyPage"></div>
    <?php else: foreach ($panels as $panel) : ?>
        <?= $panel->render(); ?>
    <?php endforeach; endif; ?>
</div>