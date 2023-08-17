<?php
// Database connection
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("config.php");

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
require_once("config.php");

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['response_content']) && isset($_POST['parent_microblog_id'])) {
        $response_content = $_POST['response_content'];
        $parent_microblog_id = $_POST['parent_microblog_id'];
        $user_id = $_SESSION['user_id']; // You might need to fetch the user ID from the session

        // Insert the microblog response into the database
        $sql = "INSERT INTO microblogs (user_id, content, parent_microblog_id) VALUES ('$user_id', '$response_content', '$parent_microblog_id')";

        if ($conn->query($sql) === TRUE) {
            // Return a success response for AJAX or redirect to the main microblog
            // You can adjust this part based on your needs
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            exit();
        }
    }
}

$conn->close();
?>
