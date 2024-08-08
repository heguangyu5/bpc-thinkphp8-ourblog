<?php

use think\facade\Session;
use think\facade\Db;

?>

<html>
<head>
<meta charset="utf-8">
<title><?php echo htmlspecialchars($title); ?> - OurBlog</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="nav-user">
        <?php if (Session::has('id')): ?>
        <a href="/admin.index"><?php echo htmlspecialchars(Session::get('username')); ?></a>
        <?php else: ?>
        <a href="/reg">Sign Up</a>
        <a href="/admin.login">Sign In</a>
        <?php endif; ?>
    </div>
    <div class="nav-links">
        <a href="/">Home</a>
        <?php
            $categoryRows = Db::query('SELECT id, name FROM category');
            foreach ($categoryRows as $row) {
                echo '<a href="/?categoryId=', $row['id'], '">', $row['name'], '</a>';
            }
        ?>
    </div>
