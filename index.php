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
// Handle posting of microblogs
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $content = $_POST["content"];
    $embed_file = $_POST["embed_file"]; // You can store the URL of the embedded file (e.g., image, video, gif) here.

    // Insert the microblog post into the database
    $sql = "INSERT INTO microblogs (user_id, content, embed_file) VALUES ('$user_id', '$content', '$embed_file')";

    if ($conn->query($sql) === TRUE) {
        // Return a success response for AJAX
        echo json_encode(array('status' => 'success', 'message' => 'Post successful!'));
        exit();
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Error: ' . $sql . "<br>" . $conn->error));
        exit();
    }
    
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Microblogging</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="mspfa.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{
            background-image:linear-gradient(to bottom, #<?php echo $user['primary_color']; ?>, #<?php echo $user['secondary_color']; ?>)
        }
    </style>
    <script src="CustomElement.js" defer></script>
    <script src="js/PostElement.js" defer></script>
</head>
<body>
    <?php require("sidebarLeft.php"); ?>
    <div id="webContent" class="webContent">
        <?php require("include/topHeader.php"); ?>
        
        <div class="allContentAndMenus">
        

            <!-- The modal popup -->
            <div id="postModal" class="postModal" style="display: none;">
                <div class="modal-content">
                    <span class="modal-close" id="modalClose">&times;</span>
                    <h3>Create a New Post</h3>
                    <form id="postForm" method="post">
                        <label for="content">Content:</label>
                        <textarea id="content" name="content" required></textarea>
                        <br>
                        <label for="embed_file">Embed File URL:</label>
                        <input type="text" id="embed_file" name="embed_file">
                        <br>
                        <input type="submit" value="Post">
                    </form>
                </div>
            </div>
            
            <button id="newPostsButton" class="newPosts" style="display: none;">Novas mensagens</button>
            <div id="postsContainer" class="postsContainer">
            
                <!-- Button to open the modal popup -->
            <button id="newPostButton" class="post-button">Novo Post</button>
                <!-- Posts will be dynamically added here -->
            </div>
        </div>
        <button id="loadMore">Load More</button> <!-- Button to load more posts -->
        <script>
            const phpUsername = "<?php echo $user['username']; ?>";
            const webContent = document.getElementById('webContent');
        </script>
        <script src="script.js"></script>
        <script>
            getMicroblogsData();
        </script>
        <br>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
<?php
$conn->close();
?>
