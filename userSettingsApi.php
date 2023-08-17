<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("config.php");
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Function to check if the given password matches the user's current password
function verifyPassword($conn, $user_id, $password) {
    $sql = "SELECT password FROM users WHERE id = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        return password_verify($password, $user['password']);
    }
    return false;
}

// Function to update user data
function updateUser($conn, $user_id, $updateData) {
    $updateFields = [];

    // Check if the provided data contains the password fields
    if (isset($updateData['current_password']) && isset($updateData['new_password'])) {
        $current_password = $updateData['current_password'];
        $new_password = $updateData['new_password'];

        // Verify the current password before proceeding with the update
        if (!verifyPassword($conn, $user_id, $current_password)) {
            header("HTTP/1.1 403 Forbidden");
            exit();
        }

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $updateFields[] = "password = '$hashed_password'";
    }

    // Check and update other user fields
    if (isset($updateData['info'])) {
        $info = $updateData['info'];
        $updateFields[] = "info = '$info'";
    }

    if (isset($updateData['nickname'])) {
        $nickname = $updateData['nickname'];
        $updateFields[] = "nickname = '$nickname'";
    }

    if (isset($updateData['tags'])) {
        $tags = $updateData['tags'];
        $updateFields[] = "tags = '$tags'";
    }

    if (isset($updateData['background'])) {
        $background = $updateData['background'];
        $updateFields[] = "background = '$background'";
    }

    if (isset($updateData['avatar'])) {
        $avatar = $updateData['avatar'];
        $updateFields[] = "avatar = '$avatar'";
    }

    // Update the user data in the database
    if (!empty($updateFields)) {
        $updateFieldsStr = implode(", ", $updateFields);
        $sql = "UPDATE users SET $updateFieldsStr WHERE id = '$user_id'";
        if ($conn->query($sql) === TRUE) {
            header("HTTP/1.1 200 OK");
            exit();
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            exit();
        }
    }
}

// Function to update userSettings data
function updateUserSettings($conn, $user_id, $updateData) {
    $updateFields = [];

    // Check and update settings in the userSettings table
    if (isset($updateData['primary_color'])) {
        $primary_color = $updateData['primary_color'];
        $updateFields[] = "primary_color = '$primary_color'";
    }

    if (isset($updateData['secondary_color'])) {
        $secondary_color = $updateData['secondary_color'];
        $updateFields[] = "secondary_color = '$secondary_color'";
    }

    if (isset($updateData['tertiary_color'])) {
        $tertiary_color = $updateData['tertiary_color'];
        $updateFields[] = "tertiary_color = '$tertiary_color'";
    }

    if (isset($updateData['language'])) {
        $language = $updateData['language'];
        $updateFields[] = "language = '$language'";
    }

    if (isset($updateData['age_restriction'])) {
        $age_restriction = $updateData['age_restriction'];
        $updateFields[] = "age_restriction = '$age_restriction'";
    }

    // Update the userSettings data in the database
    if (!empty($updateFields)) {
        $updateFieldsStr = implode(", ", $updateFields);
        $sql = "UPDATE userSettings SET $updateFieldsStr WHERE user_id = '$user_id'";
        if ($conn->query($sql) === TRUE) {
            header("HTTP/1.1 200 OK");
            exit();
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            exit();
        }
    }
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the request data (assuming the data is sent as JSON)
    $requestData = json_decode(file_get_contents('php://input'), true);

    if (!empty($requestData)) {
        // Separate user and userSettings data for updating
        $userData = array_intersect_key($requestData, array_flip(['current_password', 'new_password', 'info', 'nickname', 'tags', 'background', 'avatar']));
        $userSettingsData = array_intersect_key($requestData, array_flip(['primary_color', 'secondary_color', 'tertiary_color', 'language', 'age_restriction']));

        // Update user data
        if (!empty($userData)) {
            updateUser($conn, $user_id, $userData);
        }

        // Update userSettings data
        if (!empty($userSettingsData)) {
            updateUserSettings($conn, $user_id, $userSettingsData);
        }
    } else {
        header("HTTP/1.1 400 Bad Request");
        exit();
    }
} else {
    header("HTTP/1.1 405 Method Not Allowed");
    exit();
}

$conn->close();
?>
