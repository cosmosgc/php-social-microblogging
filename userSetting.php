<?php
// Start session (if not already started)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("config.php");


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
} 

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users, userSettings WHERE users.id = '$user_id' AND user_id = '$user_id'" ;
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Microblogging</title>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{
            background-image:linear-gradient(to bottom, #<?php echo $user['primary_color']; ?>, #<?php echo $user['secondary_color']; ?>)
        }
        .allContentAndMenus {
            display: flex;
            flex-wrap: wrap;
            flex-direction: column;
        }
        .SettingsPanel{
            display: flex;
            justify-content: flex-start;
            flex-wrap: wrap;
            flex-direction: column;
        }
        .SettingsPanel div{
            display:grid;
            grid-template-columns: max-content max-content;
            grid-gap:5px;
        }
        .SettingsPanel label{
            text-align:right;
        }
        .SettingsPanel form{
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
    </style>
</head>
<body>
    <?php require("sidebarLeft.php"); ?>
    <div id="webContent" class="webContent">
        <?php require("include/topHeader.php"); ?>
        
        <div class="allContentAndMenus">
            <h1>User Settings</h1>
            <div class="SettingsPanel">
                <h2>Change Password</h2>
                <form id="changePasswordForm">
                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" required>
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" required>
                    <input type="submit" value="Change Password">
                </form>
            </div>
            <div class="SettingsPanel">
                <h2>Update Profile</h2>
                <form id="updateProfileForm">
                    <label for="info">Info:</label>
                    <input type="text" id="info" value="<?php echo $user['info']; ?>">
                    <label for="nickname">Nickname:</label>
                    <input type="text" id="nickname" value="<?php echo $user['nickname']; ?>">
                    <label for="tags">Tags:</label>
                    <input type="text" id="tags" value="<?php echo $user['tags']; ?>">
                    <label for="avatar">Avatar URL:</label>
                    <input type="text" id="avatar" value="<?php echo $user['avatar']; ?>">
                    <label for="background">Background URL:</label>
                    <input type="text" id="background" value="<?php echo $user['background']; ?>">
                    <input type="submit" value="Update Profile">
                </form>
            </div>
            <div class="SettingsPanel">
                <h2>Update Colors</h2>
                <form id="updateColorsForm">
                    <label for="primary_color">Primary Color:</label>
                    <input type="color" id="primary_color" value="#<?php echo $user['primary_color']; ?>">
                    <label for="secondary_color">Secondary Color:</label>
                    <input type="color" id="secondary_color" value="#<?php echo $user['secondary_color']; ?>">
                    <input type="submit" value="Update Colors">
                </form>
            </div>

            <script>
                const changePasswordForm = document.getElementById('changePasswordForm');
                const updateProfileForm = document.getElementById('updateProfileForm');
                const updateColorsForm = document.getElementById('updateColorsForm');

                changePasswordForm.addEventListener('submit', (event) => {
                    event.preventDefault();

                    const currentPassword = document.getElementById('current_password').value;
                    const newPassword = document.getElementById('new_password').value;

                    const requestData = {
                        current_password: currentPassword,
                        new_password: newPassword
                    };

                    fetch('userSettingsApi.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(requestData)
                    })
                    .then((response) => {
                        if (response.ok) {
                            alert('Password changed successfully!');
                        } else if (response.status === 401) {
                            alert('Unauthorized: Please log in first.');
                        } else if (response.status === 403) {
                            alert('Invalid current password.');
                        } else {
                            alert('Failed to change password.');
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                });

                updateProfileForm.addEventListener('submit', (event) => {
                    event.preventDefault();

                    const info = document.getElementById('info').value;
                    const nickname = document.getElementById('nickname').value;
                    const tags = document.getElementById('tags').value;
                    const background = document.getElementById('background').value;
                    const avatar = document.getElementById('avatar').value;

                    const requestData = {
                        info: info,
                        nickname: nickname,
                        tags: tags,
                        background: background,
                        avatar: avatar
                    };

                    fetch('userSettingsApi.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(requestData)
                    })
                    .then((response) => {
                        if (response.ok) {
                            alert('Profile updated successfully!');
                        } else if (response.status === 401) {
                            alert('Unauthorized: Please log in first.');
                        } else {
                            alert('Failed to update profile.');
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                });

                updateColorsForm.addEventListener('submit', (event) => {
                    event.preventDefault();

                    const primaryColor = document.getElementById('primary_color').value.substring(1); // Remove the '#' from the color value
                    const secondaryColor = document.getElementById('secondary_color').value.substring(1); // Remove the '#' from the color value

                    const requestData = {
                        primary_color: primaryColor,
                        secondary_color: secondaryColor
                    };

                    fetch('userSettingsApi.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(requestData)
                    })
                    .then((response) => {
                        if (response.ok) {
                            alert('Colors updated successfully!');
                        } else if (response.status === 401) {
                            alert('Unauthorized: Please log in first.');
                        } else {
                            alert('Failed to update colors.');
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                });
            </script>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>
