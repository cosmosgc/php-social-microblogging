<!DOCTYPE html>
<html>
<head>
    <title>Microblog</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['user']['username']; ?></h1>
    <p>Your email: <?php echo $_SESSION['user']['email']; ?></p>
    <a href="index.php?action=logout">Logout</a>
    <!-- Implement your microblogging interface here -->
</body>
</html>
