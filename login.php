<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <h1>Login</h1>
    <form action="login_process.php" method="post" id="registrationForm">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <br>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <br>
        <input type="submit" value="Login">
    </form>
    <script>
        // Apply the fade-in animation after the page has loaded
        window.onload = function () {
            const form = document.getElementById('registrationForm');
            form.classList.add('fade-in');
        };
    </script>
</body>
</html>
