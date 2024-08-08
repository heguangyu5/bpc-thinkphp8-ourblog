<?php

$title = 'Home';
include __DIR__ . '/../header.php';

if (isset($_GET['categoryId'])) {
    $categoryId = OurBlog_Post::DBAIPK($_GET['categoryId']);
} else {
    $categoryId = 0;
}

$sql = 'SELECT id, title FROM post';
if ($categoryId) {
    $sql .= " WHERE category_id = $categoryId";
}

$postRows = \think\facade\Db::query($sql);
?>

<div class="posts">
<?php
    foreach ($postRows as $row) {
        echo '<a href="/post?id=', $row['id'], '">', htmlspecialchars($row['title']), '</a>';
    }
?>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
