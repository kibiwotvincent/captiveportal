<?php
    header("Expires: 0");
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");

    // URL of your online login handler
    $onlineServer = "https://offylink.bytesbay.site/";

    // If username or password is missing, redirect to online login interface
    if (!isset($_GET['u']) || !isset($_GET['p'])) {
        // Construct current URL to send as a return parameter
        $currentUrl = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $encodedReturnUrl = urlencode($currentUrl);
        
        // Redirect to the online login page
        //header("Location: " . $onlineServer . "prep-hotspot-login?r=" . $encodedReturnUrl);
        $url = "https://offylink.bytesbay.site/prep-hotspot-login?r=http%3A%2F%2F192.168.1.1%3A8002%2Findex.php%3Fzone%3Dbytesbay1%26redirurl%3Dhttp%253A%252F%252Fconnectivitycheck.gsta";
        header("Location: ".$url);
      
        exit;
    }

    // If credentials are present, assign them
    $username = htmlspecialchars($_GET['u']);
    $password = htmlspecialchars($_GET['p']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Redirecting to Internet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h2>Please wait as we log you in and redirect you to the internet...</h2>

    <!-- Hidden form for pfSense captive portal -->
    <form method="post" action="$PORTAL_ACTION$" id="login_form">
        <input type="hidden" name="auth_user" value="<?= $username ?>">
        <input type="hidden" name="auth_pass" value="<?= $password ?>">
        <input type="hidden" name="auth_voucher" value="">
        <input type="hidden" name="redirurl" value="$PORTAL_REDIRURL$">
        <input type="hidden" name="zone" value="$PORTAL_ZONE$">
        <input type="submit" name="accept" id="login_form_submit" value="Continue">
    </form>

    <script>
        window.onload = function() {
            document.getElementById("login_form_submit").click();
        };
    </script>
</body>
</html>
