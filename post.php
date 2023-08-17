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
    header("Location: login.php");
    exit();
}
// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users LEFT JOIN userSettings ON users.id = userSettings.user_id WHERE users.id = '$user_id'";

$result = $conn->query($sql);
$user = $result->fetch_assoc();
if ($user === null || $user['user_id'] === null) {
    // userSettings data doesn't exist, create a default row
    $defaultPrimaryColor = '363e6b'; // Replace this with your desired default primary color
    $defaultSecondaryColor = '9176ff'; // Replace this with your desired default secondary color

    $sqlInsertDefault = "INSERT INTO userSettings (user_id, primary_color, secondary_color) VALUES ('$user_id', '$defaultPrimaryColor', '$defaultSecondaryColor')";
    $conn->query($sqlInsertDefault);

    // Fetch the updated user data
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
}
if (isset($_GET['post']) && is_numeric($_GET['post'])) {
    //$post_id = $_GET['post'];
    // Get the page number from the request, default to 1 if not provided
    $pageNumber = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $microblogId = isset($_GET['post']) ? intval($_GET['post']) : 1;

    // Calculate the offset for pagination
    $itemsPerPage = 10; // Change this number based on how many records you want to show per page
    $offset = ($pageNumber - 1) * $itemsPerPage;

    // Fetch the main microblog post based on the provided ID
    $sqlMain = "SELECT m.*, u.username, u.avatar, u.nickname, u.post_theme
                FROM microblogs m
                JOIN users u ON m.user_id = u.id
                WHERE m.id = $microblogId";

    $resultMain = $conn->query($sqlMain);


    $mainMicroblog = $resultMain->fetch_assoc();

    // Fetch all related microblogs to the main post
    $sqlRelated = "SELECT m.*, u.username, u.avatar, u.nickname, u.post_theme
                   FROM microblogs m
                   JOIN users u ON m.user_id = u.id
                   WHERE m.parent_microblog_id = $microblogId";

    $resultRelated = $conn->query($sqlRelated);

    $responses = array();

    while ($row = $resultRelated->fetch_assoc()) {
        $responses[] = array(
            'id' => $row['id'],
            'username' => $row['username'],
            'content' => $row['content'],
            'embed_file' => $row['embed_file'],
            'avatar' => $row['avatar'],
            'created_at' => $row['created_at'],
            'nickname' => $row['nickname'],
            'post_theme' => $row['post_theme']
        );
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Post</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="mspfa.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .post_user{
            background-color:transparent;
        }
        hr{
            width: 100%;
            border-color: black
        }
        textarea{
            border-radius: 17px;
            padding: 8px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    
    <?php require("sidebarLeft.php"); ?>
    <div id="webContent" class="webContent">
        <?php require("include/topHeader.php"); ?>
        
        <div class="allContentAndMenus">
            <div id="postsContainer" class="postsContainer">
                <?php if ($mainMicroblog): ?>
                    
                    <div class="post_item">
                        <div class="post_user">
                            <img src="<?php echo $mainMicroblog['avatar']; ?>" alt="User Avatar">
                            <strong><?php echo $mainMicroblog['nickname']; ?></strong>
                        </div>
                        <p><?php echo $mainMicroblog['content']; ?></p>
                        <?php if ($mainMicroblog['embed_file']): ?>
                            <img src="<?php echo $mainMicroblog['embed_file']; ?>" alt="Embedded File">
                        <?php endif; ?>
                        <small>Postado em: <?php echo $mainMicroblog['created_at']; ?></small>
                        <hr>
                        <h4>Comentários:</h4>
                        <ul>
                            <?php foreach ($responses as $response): ?>
                                <li>
                                    <div class="post_item">
                                        <div class="post_user">
                                            <img src="<?php echo $response['avatar']; ?>" alt="User Avatar">
                                            <strong><?php echo $response['nickname']; ?></strong>
                                        </div>
                                        <?php echo $response['content']; ?>
                                        <small>Postado em: <?php echo $response['created_at']; ?></small>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                            
                            <form id="responseForm" method="post" action="postApi.php">
                                <label for="response_content">Comente:</label>
                                <textarea id="response_content" name="response_content" required></textarea>
                                <input type="hidden" name="parent_microblog_id" value="<?php echo $mainMicroblog['id']; ?>">
                                <br>
                                <input type="submit" value="Enviar">
                            </form>
                        </ul>
                    </div>
                <?php else: ?>
                    <p>No post found with the specified ID.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <a href="logout.php">Logout</a>
    </div>
</body>

</html>
