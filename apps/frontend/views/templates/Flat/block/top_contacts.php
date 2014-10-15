<p class="telef"><?= varlang('nr-phone'); ?></p>
<div class="cont">
    <a class="contact_us"><?= varlang('contact-us'); ?></a>
    <div class="cont_form hidden">
        <div class="relative">
            <img src="<?= res('assets/img/c_arrow.png'); ?>">
        </div>
        <p class="title"><?= varlang('date-contact'); ?></p>
        <div class="d_hr"></div>
        <ul>
            <li>
                <img src="<?= res('assets/img/c_mail.png'); ?>">
                <p><?= varlang('email'); ?></p>
                <p><a href="mailto:<?= varlang('email-address'); ?>"> <?= varlang('email-address'); ?></a></p>
            </li>
            <li>
                <img src="<?= res('assets/img/c_phone.png'); ?>">
                <p><?= varlang('relatii'); ?></p>
                <p><?= varlang('nr-relatii'); ?></p>
            </li>
            <li>
                <img src="<?= res('assets/img/c_fx.png'); ?>">
                <p><?= varlang('fax'); ?></p>
                <p><?= varlang('nr-fax'); ?></p>
            </li>
            <div class="clearfix"></div>
        </ul>
        <?php if (isset($phone_page) && $phone_page) { ?>
            <div class="prp">
                <a href="<?= Core\APL\Language::url('topost/' . $phone_page->id); ?>"><?= varlang('all-nr-phone'); ?></a>
            </div>
        <?php } ?>
        <div class="prp">
            <a href="<?= varlang('orar-link'); ?>"><?= varlang('orar-autobus'); ?></a>
        </div>
        <div class="left c_info"  onclick="window.open('https://www.google.ro/maps/dir//' + loc_lat + ',' + loc_long + '/@' + loc_lat + ',' + loc_long + ',14z');">
            <p class="city"><?= varlang('address'); ?></p>
            <p class="street"><?= varlang('street'); ?></p>
            <p class="street"><?= varlang('city'); ?></p>
        </div>
        <div class="right map">
            <div id="map-canvas" style="width: 158px; height: 119px;"></div>
        </div>
        <div class="clearfix"></div>
        <p class="form_title"><?= varlang('scrieti-direct'); ?></p>
        <div class="contact_top_notif adv" style="display: none;"><?= varlang('success'); ?> </div>
        <form id="contact_top_form" action="<?= url(); ?>" method="post">
            <div class="form_error"></div>
            <input type="text" name="name" placeholder="<?= varlang('name-last-name'); ?>">
            <input type="text" name="email" placeholder="<?= varlang('email'); ?>">
            <textarea name="message" placeholder="<?= varlang('message'); ?>"></textarea>
            <input name="capcha" class="code" type="text">
            <img src="<?= SimpleCapcha::make('contact_top'); ?>" height="37">
            <input type="submit" value="<?= varlang('submit'); ?>">
        </form>
    </div>                       
</div>