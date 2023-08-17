<?php
// Database connection
require_once("config.php");

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the microblog ID from the request
$microblogId = isset($_GET['microblog_id']) ? intval($_GET['microblog_id']) : null;
if (!isset($microblogId)){
    $microblogId = isset($_GET['post']) ? intval($_GET['post']) : null;
}
// Prepare the SQL query to retrieve the microblog and its recursive responses
    $sql = "SELECT m.*, u.username, u.avatar, u.nickname, u.post_theme,
    r.id AS response_id, r.content AS response_content, r.created_at AS response_created_at, 
    ru.username AS response_username, ru.avatar AS response_avatar, ru.nickname AS response_nickname
    FROM microblogs m
    JOIN users u ON m.user_id = u.id
    LEFT JOIN microblogs r ON m.id = r.parent_microblog_id
    LEFT JOIN users ru ON r.user_id = ru.id
    WHERE m.id = $microblogId OR m.parent_microblog_id = $microblogId
    ORDER BY m.id DESC, r.id ASC";

$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['id'] == $microblogId || $row['parent_microblog_id'] == $microblogId) {
            $item = array(
                'id' => $row['id'],
                'username' => $row['username'],
                'content' => $row['content'],
                'embed_file' => $row['embed_file'],
                'avatar' => $row['avatar'],
                'created_at' => $row['created_at'],
                'nickname' => $row['nickname'],
                'post_theme' => $row['post_theme']
            );

            if ($row['response_id']) {
                $response = array(
                    'id' => $row['response_id'],
                    'content' => $row['response_content'],
                    'created_at' => $row['response_created_at'],
                    'username' => $row['response_username'],
                    'avatar' => $row['response_avatar']
                );

                $item['response'] = $response;
            }

            $data[] = $item;
        }
    }
}

$conn->close();

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
