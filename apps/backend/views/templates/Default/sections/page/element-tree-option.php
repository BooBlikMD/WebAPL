<?php
$count = count($items);
foreach ($items as $k => $item) {
    $nodeName = isset($item['lang']['name']) && $item['lang']['name'] ? $item['lang']['name'] : "Page #{$item->id}";
    ?>
    <option value='<?= $item->id; ?>' <?= isset($page->parent) && $page->parent == $item->id ? 'selected' : ''; ?>><?= $level - 1 ? str_repeat('┃', $level - 1) : ""; ?><?= $count == $k + 1 ? '┗' : '┣' ?><?= $nodeName; ?></option>
    <?php
    if ($item['list']) {
        echo View::make('sections.page.element-tree-option', array('level' => $level + 1, 'items' => $item['list']));
    }
}
?>