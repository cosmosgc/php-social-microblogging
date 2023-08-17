<?php
session_start();
require_once 'config.php';
require_once 'discordOAuth.php';

// Handle login/logout actions
if (isset($_GET['action']) && $_GET['action'] === 'login') {
    redirectToDiscordAuthorization();
} elseif (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout();
}

// Check if the user is logged in or not
if (isset($_SESSION['user'])) {
    // User is logged in, show microblogging page
    include 'microblog.php';
} else {
    // User is not logged in, show login page
    include 'login.php';
}
?>
