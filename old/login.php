<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login with Discord</h1>
    <?php
    if (isset($_GET['error'])) {
        echo '<p style="color: red;">Authentication failed. Please try again.</p>';
    }
    ?>
    <a href="index.php?action=login">Login with Discord</a>
</body>
</html>
