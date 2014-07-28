<form class="ajax-auto-submit" action='<?= url('feed/postsave'); ?>' method='post'>
    <input type='hidden' name='id' value='<?= isset($post->id) ? $post->id : 0; ?>' />

    <table class="table table-bordered">
        <tr>
            <th>Date: </th>
            <td>
                <input type="text" name="post[created_at]" class='form-control' value='<?= isset($post->created_at) ? $post->created_at : ''; ?>' />
            </td>
        </tr>

        <tr>
            <th>Feeds: </th>
            <td>
                <input name='feed_post[]' value='0' type='hidden' />
                <select name='feed_post[]' multiple='multiple' class='col-sm-12 chzn-select'>
                    <option></option>
                    <?php foreach ($feeds as $feed) { ?>
                        <option value='<?= $feed->id; ?>' <?= in_array($feed->id, $post_feeds) ? 'selected' : '' ?>><?= $feed->name; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>

        <?php foreach ($fields as $field) { ?>
            <tr>
                <th><?=$field->title;?>: </th>
                <td>
                    <?=dinamic_field($field, array('{name}'=>"dinamic_post[{$field->fkey}]", '{value}' => $field->value, '{class}' => 'form-control'));?>
                </td>
            </tr>
        <?php } ?>
    </table>
    
</form>
