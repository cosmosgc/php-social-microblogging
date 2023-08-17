<?php
// Database connection
require_once("config.php");

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the last post ID from the query parameters
$lastPostId = isset($_GET['lastPostId']) ? intval($_GET['lastPostId']) : 0;

// Fetch microblogs count to check for new posts
$sql = "SELECT COUNT(*) AS num_posts FROM microblogs WHERE id > $lastPostId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $numPosts = intval($row['num_posts']);

    // Return the JSON response
    header('Content-Type: application/json');
    echo json_encode(array('newPosts' => $numPosts > 0));
} else {
    // Return an error JSON response if the query failed
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Error checking for new posts.'));
}

$conn->close();
?>
