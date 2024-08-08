<html>
<head>
<meta charset="utf-8">
<title>OurBlog - Sign In</title>
<link rel="stylesheet" href="/style.css">
</head>
<body>

<div class="container">
    <h2>OurBlog Sign In</h2>
    <?php
        if (isset($_GET['activate']) && $_GET['activate'] == 'success') {
            echo '<p class="success">activate success!</p>';
        }
    ?>
    <form method="POST">
        <?php
            if ($error) {
                echo '<p class="error">', htmlspecialchars($error),'</p>';
            }
        ?>
        <label>Email:</label><input type="email" name="email" required><br><br>
        <label>Password:</label><input type="password" name="password" required><br><br>
        <label></label><input type="submit" value="Sign In">
    </form>
</div>

</body>
</html>
