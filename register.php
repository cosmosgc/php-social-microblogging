<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <h1>Registration</h1>
    <form action="register_process.php" method="post" id="registrationForm">
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
        <input type="submit" value="Register">
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
