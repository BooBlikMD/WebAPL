<div id="firechat" class="dop" style="display: <?= $session_exist ? 'block' : 'none'; ?>;">
    <div class="top">
        <div class="left firechat-photo" style=" display: <?= $session_exist ? 'block' : 'none'; ?>;">
            <div class="photo">
                <img src="<?= isset($person_icon) ? $person_icon->path : ''; ?>">
            </div>
        </div>

        <div class="right" >
            <div class="buttons">
                <button class="firechat-hide"><img src="<?= res('assets/img/save.png'); ?>"></button>
                <button class="firechat-close"><img src="<?= res('assets/img/close.png'); ?>"></button>
            </div>
        </div>
        <div class="right firechat-name" style="display:<?= $session_exist ? 'block' : 'none'; ?>;">
            <p class="c_name">
                Discută on-line cu
                primarul <span class="firechat-person"><?= isset($person) ? $person->first_name . " " . $person->last_name : ''; ?></span>
            </p>
            <p class="status on">
                on-line
            </p>
        </div>
        <hr>
    </div>
    <div class="content">

        <?php if ($session_exist) { ?>
            <?= Core\APL\Template::moduleView('firechat', 'views.chat-iframe'); ?>
        <?php } else { ?>
            <div class="form green">
                <form class="firechat-register" action="" method="">
                    <div class="contenta">
                        <label>Functionar *</label>
                        <select name="person_id">
                            <?php foreach ($persons as $person) { ?>
                                <option value="<?= $person->id; ?>"><?= $person->first_name; ?> <?= $person->last_name; ?></option>
                            <?php } ?>
                        </select>
                        <label>Numele Prenumele * </label>
                        <input name="name" type="text" >
                        <label>Email*</label>
                        <input name="email" type="text" >    
                        <input type="submit" value="trimite">
                        <div class="clearfix"></div>
                    </div>
                </form>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    $(document).ready(function($) {
        //$("#firechat")
        $("body").on("click", ".firechat-start", function() {
            $("#firechat").stop().slideToggle(500).animate({height: 630}, 500);
        });

        $("body").on("click", ".firechat-close", function() {
            $("#firechat").slideToggle(500);
            jQuery.post('<?= url('firechat/close'); ?>', {});
        });

        $("body").on("click", ".firechat-hide", function() {
            $("#firechat .content").slideToggle(500);
            $("#firechat").animate({height: 103}, 500);
            $(this).removeClass("firechat-hide").addClass("firechat-show");
        });

        $("body").on("click", ".firechat-show", function() {
            $("#firechat .content").slideToggle(500);
            $("#firechat").animate({height: 630}, 500);
            $(this).removeClass("firechat-show").addClass("firechat-hide");
        });

        $("body").on("submit", ".firechat-register", function(e) {
            e.preventDefault();
            console.log($(".firechat-register").serialize());
            $.post('<?= url('firechat/register'); ?>', $(this).serialize(), function(data) {
                if (data.error === 0) {
                    $("#firechat .content").html(data.html);
                    $(".firechat-person").text(data.person.first_name + " " + data.person.last_name);
                    $(".firechat-photo img").attr('src', data.person.photo);

                    $(".firechat-photo, .firechat-name").show();
                } else {
                    alert('Chat error!');
                    //window.location.reload();
                }
            }, 'json');

            return false;
        });
    });
</script>