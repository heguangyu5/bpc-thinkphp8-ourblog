<?php

use think\facade\Db;
use think\facade\Session;

$title = 'Admin';
include __DIR__ . '/../header.php';

if (isset($_GET['categoryId'])) {
    $categoryId = OurBlog_Post::DBAIPK($_GET['categoryId']);
} else {
    $categoryId = 0;
}

$q = Db::table('post')
        ->field('id,title')
        ->where('user_id', Session::get('id'));
if ($categoryId) {
    $q->where('category_id', $categoryId);
}

$postRows = $q->select();
?>

<table>
    <thead>
        <tr>
            <th width="50">ID</th>
            <th>Title</th>
            <th width="150">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($postRows as $row): ?>
        <tr>
            <td align="center"><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td align="center">
                <a href="/admin.post/edit?id=<?php echo $row['id']; ?>">Edit</a>
                <a href="/admin.post/delete?id=<?php echo $row['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../footer.php'; ?>
