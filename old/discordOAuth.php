<?php
require_once 'config.php';

// Function to redirect the user to the Discord authorization page
function redirectToDiscordAuthorization()
{
    $discordAuthUrl = 'https://discord.com/api/oauth2/authorize' .
                      '?client_id=' . CLIENT_ID .
                      '&redirect_uri=' . urlencode(REDIRECT_URI) .
                      '&response_type=code' .
                      '&scope=identify email';

    header('Location: ' . $discordAuthUrl);
    exit;
}

// Function to handle the callback from Discord after the user grants permission
function handleDiscordCallback()
{
    if (isset($_GET['code'])) {
        $code = $_GET['code'];
        // Exchange the authorization code for an access token
        $redirect_url = 'http://your_web_app_domain/callback.php';
        $client_id = 'your_discord_client_id';
        $client_secret = 'your_discord_client_secret';
        init($redirect_url, $client_id, $client_secret);

        // Retrieve user information using the access token
        $user_data = getUserInfo($_SESSION['access_token']);

        if ($user_data) {
            // Save relevant user information in the session
            $_SESSION['user'] = [
                'username' => $user_data['username'],
                'email' => $user_data['email'],
            ];

            // Redirect the user back to the microblogging page
            header('Location: social/');
            exit;
        }
    } else {
        // Handle error response from Discord
        // Redirect the user to the login page with an error message
        header('Location: social/login.php?error=auth_failed');
        exit;
    }
}

function getUserInfo($access_token)
{
    $url = 'https://discord.com/api/users/@me';
    $headers = [
        'Authorization: Bearer ' . $access_token,
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
}

function init($redirect_url, $client_id, $client_secret, $bot_token = null)
{
    if ($bot_token != null)
        $GLOBALS['bot_token'] = $bot_token;
    $code = $_GET['code'];
    $state = $_GET['state'];
    # Check if $state == $_SESSION['state'] to verify if the login is legit | CHECK THE FUNCTION get_state($state) FOR MORE INFORMATION.
    $url = $GLOBALS['base_url'] . "/api/oauth2/token";
    $data = array(
        "client_id" => $client_id,
        "client_secret" => $client_secret,
        "grant_type" => "authorization_code",
        "code" => $code,
        "redirect_uri" => $redirect_url
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    $_SESSION['access_token'] = $results['access_token'];
}

// Function to log the user out
function logout()
{
    session_unset();
    session_destroy();
    header('Location: social/login.php');
    exit;
}
?>
