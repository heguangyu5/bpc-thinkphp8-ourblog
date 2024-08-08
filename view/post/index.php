<?php

$title = $post['title'];
include __DIR__ . '/../header.php';

?>

<h3><?php echo htmlspecialchars($title); ?></h3>
<p class="post-meta">
    <?php echo htmlspecialchars($post['username']); ?> created at <?php echo $post['create_date']; ?>
</p>
<div class="post-content"><?php echo htmlspecialchars($post['content']); ?></div>

<?php include __DIR__ . '/../footer.php'; ?>
