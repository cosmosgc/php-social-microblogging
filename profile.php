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
/*
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}*/

// Fetch user details from the database
if (isset($_GET['user']) && !empty($_GET['user'])) {
    $user_param = $_GET['user'];

    // Check if the user parameter is numeric (likely an ID) or alphanumeric (likely a username)
    if (is_numeric($user_param)) {
        $where_clause = "id = '$user_param'";
    } else {
        $where_clause = "username = '$user_param'";
    }
} else {
    $user_id = $_SESSION['user_id'];
    $where_clause = "id = '$user_id'";
    $user_param = $user_id;
}

// Fetch user data from the database
$sql = "SELECT * FROM users WHERE $where_clause";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

function escape_for_open_graph($data) {
    // This function escapes and sanitizes the data to be used in Open Graph meta tags
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Microblogging</title>
    <meta property="og:title" content="<?php echo escape_for_open_graph($user['username']); ?>" />
    <meta property="og:type" content="profile" />
    <meta property="og:url" content="<?php echo escape_for_open_graph($_SERVER['REQUEST_URI']); ?>" />
    <meta property="og:image" content="<?php echo escape_for_open_graph($user['avatar']); ?>" />
    <meta property="og:description" content="<?php echo escape_for_open_graph($user['info']); ?>" />
    <meta property="profile:first_name" content="<?php echo escape_for_open_graph($user['nickname']); ?>" />
    <meta property="profile:username" content="<?php echo escape_for_open_graph($user['username']); ?>" />
    <?php if (!empty($user['tags'])): ?>
        <meta property="profile:tags" content="<?php echo escape_for_open_graph($user['tags']); ?>" />
    <?php endif; ?>
    <?php if (!empty($user['background'])): ?>
        <meta property="og:image:background" content="<?php echo escape_for_open_graph($user['background']); ?>" />
    <?php endif; ?>
    <script src="js/PostElement.js" defer></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        body{
            background-image:linear-gradient(to bottom, #<?php echo $user['primary_color']; ?>, #<?php echo $user['secondary_color']; ?>)
        }
    </style>
    <style>
        .profile-container {
            margin: 0 auto;
            padding: 0px;
            width: 100%;
            <?php if (!empty($user['background'])) : ?>
                background-image: url("<?php echo $user['background']; ?>");
                background-size: cover;
                background-position: center;
            <?php endif; ?>
            border-top-left-radius: 17px;
            border-top-right-radius: 0px;
        }

        .profile-avatar {
            max-width: 150px;
            border-radius: 50%;
            margin: 0 auto;
            display: block;
        }

        .profile-info {
            margin-top: 20px;
            margin-bottom: 0px;
            <?php if (!empty($user['background'])) : ?>
                background-color: #ff758f6e;
            <?php endif; ?>
        }
        .profile-info p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <?php require("sidebarLeft.php"); ?>
    <div id="webContent" class="webContent">
        <div class="profile-container">
            <img src="<?php echo $user['avatar']; ?>" alt="Avatar" class="profile-avatar">
            <div class="profile-info">
                <h1><?php echo $user['username']; ?></h1>
                <p><strong>Nickname:</strong> <?php echo $user['nickname']; ?></p>
                <p><strong>Tags:</strong> <?php echo $user['tags']; ?></p>
                <p><strong>Info:</strong> <?php echo $user['info']; ?></p>
            </div>
        </div>
        <button id="newPostsButton" class="newPosts" style="display: none;">Novas mensagens</button>

        <div id="postsContainer" class="postsContainer">
            <button id="newPostButton" class="post-button">Novo Post</button>
                <!-- Posts will be dynamically added here -->
        </div>
        <button id="loadMore">Load More</button> <!-- Button to load more posts -->
    </div>
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
    <script>
            const phpUsername = "<?php echo $user['username']; ?>";
            const webContent = document.getElementById('webContent');
        </script>
        <script src="script.js"></script>
        <script>
            pageParam = `?user=<?php echo $user_param;?>`;
            getMicroblogsData();
        </script>
        <style>
            .post-button{
                display: none;
            }
        </style>
    
</body>
</html>
<?php
$conn->close();
?>