<?php if (isset($posts)) { ?>
    <?php foreach ($posts as $item) { ?>
        <div class="a_box g_g">
            <p class="title"><a href="<?= $page_url; ?>?item=<?= $item->uri; ?>"><?= $item->title; ?></a></p>
            <div class="hr_dbl"></div>
            <div class="left">
                <div class="img">
                    <?php if ($item->cover) { ?>
                        <img alt="<?= $item->title; ?>" title="<?= $item->title; ?>" src="<?= url($item->cover['path']); ?>">
                    <?php } ?>
                </div>
                <?php if (strtotime($item->created_at)) { ?>
                    <div class="details">
                        <p class="data"><?= date("d-m-Y, H:i", strtotime($item->created_at)); ?></p>
                        <p></p>
                    </div>
                <?php } ?>
            </div>
            <div class="right">
                <p class="info"><?= Str::words(strip_tags($item->text), 55); ?></p>
            </div>
            <a href="<?= $page_url; ?>?item=<?= $item->uri; ?>" class="more"></a>
            <div class="clearfix"></div>
        </div>
    <?php } ?>

    <?php
    if (method_exists($posts, 'links')) {
        echo $posts->appends(array('year' => $current_year, 'month' => $current_month))->links();
    }
    ?>
<?php } ?>