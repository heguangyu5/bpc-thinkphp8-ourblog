<html>
<head>
<meta charset="utf-8">
<title><?php echo htmlspecialchars($title); ?> - OurBlog</title>
<link rel="stylesheet" href="/style.css">
</head>
<body>

<div class="container">
    <div class="nav-user">
        <span><?php echo htmlspecialchars(\think\facade\Session::get('username')); ?></span>
        <a href="/admin.post/add">Write Post</a>
        <a href="/admin.logout">Logout</a>
    </div>
    <div class="nav-links">
        <a href="/admin.index">Home</a>
        <?php
            $categoryRows = \think\facade\Db::table('category')->field('id,name')->select();
            foreach ($categoryRows as $row) {
                echo '<a href="/admin.index?categoryId=', $row['id'], '">', $row['name'], '</a>';
            }
        ?>
    </div>
