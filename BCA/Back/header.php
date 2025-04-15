<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>BCA: Bonsai Coach Academie</title>
    <meta name="description" content="Plateforme d'apprentissage de l'art du bonsaï">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b30f5d3ef8.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./style.css">
</head>

<body>

    <header class="container">

        <?php

        session_start();

        if (isset($_COOKIE['mail']) || isset($_SESSION['mail'])) {
            $id = isset($_COOKIE['mail']) ? $_COOKIE['mail'] : $_SESSION['mail'];
            echo '
            <nav>
                <ul id="connection">
                    <li id="signup">
                        <i class="fas fa-user"></i> ' . $id . '
                    </li>
                    <li>
                        <i class="fas fa-lock"></i> <a href="userPasswordReset.php">Change password</a>
                    </li>
                    <li id="signout">
                        <a href="userDisconnection.php"><i class="fas fa-sign-out-alt"></i></a>
                    </li>
                </ul>
            </nav>
            ';
        } else {
            echo '
            <nav>
                <ul id="connection">
                    <li id="signup">
                        <a href="userRegistrationForm.php"><i class="fas fa-user-plus"></i> Inscription</a>
                    </li>
                    <li id="signin">
                        <a href="userConnectionForm.php"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                    </li>
                </ul>
            </nav> 
            ';
        }
        ?>
        <div id="background">
            <div id="stars"></div>
            <div id="stars2"></div>
            <div id="stars3"></div>
        </div>

    </header>

</body>