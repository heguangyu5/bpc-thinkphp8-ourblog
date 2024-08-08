<html>
<head>
<meta charset="utf-8">
<title>OurBlog - Sign Up</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>OurBlog Sign Up</h2>
    <form method="POST">
        <?php
            if ($error) {
                echo '<p class="error">', htmlspecialchars($error),'</p>';
            }
        ?>
        <label>Email:</label><input type="email" name="email" required><br><br>
        <label>Username:</label><input type="text" name="username" required><br><br>
        <label>Password:</label><input type="password" name="password" required><br><br>
        <label>Confirm Password:</label><input type="password" name="confirmPassword" required><br><br>
        <label></label><input type="submit" value="Sign Up">
    </form>
</div>

</body>
</html>
