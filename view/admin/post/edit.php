<?php

$title = 'Edit Post';
include __DIR__ . '/../header.php';

?>

<h2>Edit Post</h2>
<form method="POST">
    <?php
        if ($error) {
            echo '<p class="error">', htmlspecialchars($error),'</p>';
        }
    ?>

    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <label>Category:</label><br>
    <select name="categoryId">
        <option value="">--Select--</option>
        <?php
            foreach ($categoryRows as $row) {
                echo '<option value="', $row['id'], '"', ($row['id'] == $postRow['category_id'] ? ' selected' : ''), '>', $row['name'], '</option>';
            }
        ?>
    </select>
    <br><br>

    Title:<br>
    <input type="text" name="title" style="width:80%" value="<?php echo htmlspecialchars($postRow['title']); ?>">
    <br><br>
    External Post? <input type="checkbox" name="external" value="1"<?php echo $postRow['is_external'] ? ' checked' : ''; ?>>
<br><br>
    Content:<br>
    <textarea name="content" rows="20" cols="100"><?php echo htmlspecialchars($postRow['content']); ?></textarea>
    <br><br>
    <?php
        $tags = \think\facade\Db::table('post_tag')
                ->alias('pt')
                ->join('tag t', 'pt.tag_id = t.id')
                ->where('pt.post_id', $id)
                ->order('pt.id')
                ->column('t.name');
        $tags = implode(',', $tags);
    ?>
    Tags (multiple tags separated by ,):<br>
    <input type="text" name="tags" style="width: 80%" value="<?php echo htmlspecialchars($tags); ?>">
    <br><br>

    <input type="submit" value="Save">
</form>

<?php include __DIR__ . '/../footer.php'; ?>
