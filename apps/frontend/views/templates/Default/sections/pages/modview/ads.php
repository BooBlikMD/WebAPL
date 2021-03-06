<?php if (isset($post) && $post) { ?>
    <div class="a_box v_g">
        <p class="title"><?= $post->title; ?></p>
        <div class="hr_dbl"></div>
        <div class="left">
            <div class="info unic">
                <div class="img">
                    <?php if ($post->cover) { ?>
                        <img alt="<?= $post->title; ?>" title="<?= $post->title; ?>" src="<?= url($post->cover['path']); ?>">
                    <?php } ?>
                    <?php if (strtotime($post->created_at)) { ?>
                        <div class="details">
                            <p class="data"><?= date("d-m-Y, H:i", strtotime($post->created_at)); ?></p>
                            <p></p>
                        </div>
                    <?php } ?>
                </div>
                <?= WebAPL\Shortcodes::execute($post->text); ?>
                <?php if ($post->have_socials) { ?>
                    <?= View::make('sections.elements.socials'); ?>
                <?php } ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
<?php } ?>

<?php if (isset($posts) && count($posts)) { ?>
    <div class='list'>
        <ul class='a_n'>
            <?php foreach ($posts as $item) { ?>
                <li>
                    <a href='<?= $page_url; ?>?item=<?= $item->uri; ?>'>
                        <?php if (strtotime($item->created_at)) { ?>
                            <span><?= date('d-m-Y', strtotime($item->created_at)); ?> </span>
                        <?php } ?>
                        <p><?= $item->title; ?></p>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <?php
    if (method_exists($posts, 'links')) {
        echo $posts->appends(array('year' => $current_year, 'month' => $current_month))->links();
    }
    ?>
<?php } ?>

<?php if (isset($post) && $post->have_comments) { ?>
    <div class="c40"></div>
    <?= View::make('sections.elements.comments'); ?>
<?php } ?>

<?php if (!isset($post) && !isset($posts)) { ?>
    <?= varlang('articole-null'); ?>
<?php } ?>