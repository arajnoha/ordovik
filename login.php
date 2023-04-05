<?php
session_start();
if (isset($_SESSION["in"])) {
    header("Location: ./");
    exit;
}

$error = "";

if (isset($_POST["submit"])) {

    if ($_POST["login"] === "panorama") {
        $_SESSION["in"] = 1;
        header("Location: ./");
    } else {
        $error = "Zadali jste chybné heslo.";
    }
}

?>

<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8">
    <title>Týdeník</title>
    <link rel="stylesheet" type="text/css" href="neon.css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" href="assets/favicon.png" type="image/png" size="512x512">
</head>
<body class="login-page">
    <form action="login.php" method="post" class="login-form">
        <h4>Zadejte heslo</h4>
        <p><?php echo $error;?></p>
        <input id="login" name="login" type="password" autofocus>
        <input type="submit" name="submit" value="Přihlásit">
    </form>
</body>