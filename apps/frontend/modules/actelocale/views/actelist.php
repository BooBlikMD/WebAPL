<div class="search left">
    <input type="text" id="actetitle" placeholder="<?= varlang('search-label'); ?>">
    <input type="submit" id="actesearch" value="<?= varlang('search-button'); ?>" class="green_sub">
    <div class="clearfix"></div>
</div>
<div class="sort_b">
    <p><?= varlang('page-result'); ?> </p>
    <select id="jsperpage">
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
    </select>
</div>
<?php
$months = array(
    1 => varlang('ianuarie'),
    2 => varlang('februarie'),
    3 => varlang('martie'),
    4 => varlang('aprilie'),
    5 => varlang('mai'),
    6 => varlang('iunie'),
    7 => varlang('iulie'),
    8 => varlang('august'),
    9 => varlang('septembrie'),
    10 => varlang('octombrie'),
    11 => varlang('noiembrie'),
    12 => varlang('decembrie')
);
?>

<div class="clearfix"></div>
<div class="clndr">
    <div class="calendar_slider">
        <?php foreach ($months as $month_num => $month) { ?>
            <div class="slide" data-page="1">
                <table>
                    <thead>
                        <tr><td colspan="5"><?= $month; ?> <?= $current_year; ?></td></tr>
                        <tr>
                            <td><?= varlang('acte-nr'); ?></td>
                            <td><?= varlang('acte-title'); ?></td>
                            <td><?= varlang('acte-tip'); ?></td>
                            <td><?= varlang('acte-emitent'); ?></td>
                            <td><?= varlang('acte-data'); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($list[$month_num])) { ?>
                            <?php foreach ($list[$month_num] as $doc) { ?>
                                <tr>
                                    <td><?= $doc->doc_nr; ?></td>
                                    <td class="searchin"><a href="<?=url($doc->path);?>"><?= $doc->title; ?></a></td>
                                    <td><?= $doc->type; ?></td>
                                    <td><?= $doc->emitent; ?></td>
                                    <td><?= date("d.m.Y", strtotime($doc->date_upload)); ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="jspages pag">
                    <span class="w_p"><?= varlang('acte-search'); ?></span>
                    <ul></ul>
                </div>
                <div class="clearfix10"></div>
            </div>
        <?php } ?>
    </div>
    <div class="clearfix"></div>
</div>

<script>
    var start_month = <?= $current_month - 1; ?>;

    var generatePagination = function(total, perpage) {
        $(".calendar_slider .slide").each(function() {
            var currentPage = parseInt($(this).attr('data-page'));
            var total = 0;
            if (queryTitle.length === 0) {
                total = $(this).find('tbody tr').length;
            } else {
                total = $(this).find('tbody .searchin:contains("' + queryTitle + '")').closest("tr").length;
            }
            var pages = Math.ceil(total / perPage);

            $(this).find(".jspages ul").html('');
            if (pages > 1) {
                
                $(this).find("tbody tr").hide();
                var rowStart = (currentPage - 1) * perPage;
                var rowEnd = rowStart + perPage;
                if (queryTitle.length === 0) {
                    $(this).find('tbody tr').slice(rowStart, rowEnd).show();
                } else {
                    $(this).find('tbody .searchin:contains("' + queryTitle + '")').closest("tr").slice(rowStart, rowEnd).show();
                }
                
                
                $(this).find(".jspages").show();
                for (var page = 1; page <= pages; page++) {
                    if (currentPage === page) {
                        $(this).find(".jspages ul").append('<li class="active"><span>' + page + '</span></li>');
                    } else {
                        $(this).find(".jspages ul").append('<li><a href=\'javascript:;\' class=\'pgcl\' data-page="' + page + '">' + page + '</a></li>');
                    }
                }
            } else {
                $(this).find(".jspages").hide();
                $(this).find("tbody tr").show();
            }
        });
    }


    var queryTitle = '';
    var perPage = 10;
    jQuery(document).ready(function($) {
        perPage = parseInt($("#jsperpage").val());

        generatePagination();

        $("#jsperpage").on('change', function() {
            perPage = parseInt($(this).val());
            generatePagination();
        });

        $('body').on('click', ".pgcl", function() {
            var page = $(this).attr('data-page');
            $(this).closest(".slide").attr('data-page', page);
            generatePagination();
        });

        $("#actesearch").click(function() {
            queryTitle = $("#actetitle").val();
            if (queryTitle.length > 0) {
                $(".calendar_slider tbody tr").hide();
                $('.calendar_slider tbody .searchin:contains("' + queryTitle + '")').closest("tr").show();
            } else {
                $(".calendar_slider tbody tr").show();
            }
            generatePagination();
        });
    });
</script>