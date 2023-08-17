<?php 
function escape_for_open_graph($data) {
    // This function escapes and sanitizes the data to be used in Open Graph meta tags
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
?>

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