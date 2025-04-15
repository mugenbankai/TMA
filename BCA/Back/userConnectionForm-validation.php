<?php
// Afficher les erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

$return = '';

// Vérification du formulaire
if (isset($_POST["valid"])) {
    if (isset($_POST["bca-mail"]) && $_POST["bca-mail"] != '') {
        require_once('env.php');

        $mail = $_POST["bca-mail"];
        $pwd = $_POST["bca-pwd"];

        $sql = "SELECT * FROM Apprenant WHERE Email = ?";
        $params = array($mail);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if ($result) {
            if (password_verify($pwd, $result['MotDePasse'])) {
                $return = "Connexion réussie";

                if (isset($_POST['bca-stayIn'])) {
                    $expire = 365 * 24 * 3600; // 1 an
                    setcookie("mail", $mail, time() + $expire);
                } else {
                    session_start();
                    $_SESSION['mail'] = $mail;
                    echo 'ok ' . $_SESSION['mail'];
                }
            } else {
                $return = '<span style="color:red">Mauvais mot de passe, <a href="userPasswordReset.php">réinitialisation du mot de passe</a>.</span>';
            }
        } else {
            $return = '<span style="color:red">Pas de mail correspondant</span>';
        }
    } else {
        $return = '<span style="color:red">Veuillez préciser un mail</span>';
    }
} else {
    $return = '<span style="color:red">Formulaire non validé</span>';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bonsai Coach Academie</title>
    <meta name="description" content="Plateforme de peer-learning pour l'art du bonsaï">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="bonsai.css">
    <script src="https://kit.fontawesome.com/b30f5d3ef8.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="container">
    <header>
        <nav>
            <ul id="connection">
                <li id="signup">
                    <a href="userRegistrationForm.php"><i class="fas fa-user-plus"></i> Inscription</a>
                </li>
            </ul>
        </nav>
        <div class="cleared"></div>

        <h1><?php echo $return; ?></h1>
    </header>

    <?php
    if ($return != 'Connexion réussie') {
        echo '
        <section>
            <form action="userConnectionForm-validation.php" method="post">
                <table>
                    <tr>
                        <td class="label">Mail</td>
                        <td><input type="email" name="bca-mail" required></td>
                    </tr>
                    <tr>
                        <td class="label">Mot de passe</td>
                        <td><input type="password" name="bca-pwd" required></td>
                    </tr>
                    <tr>
                        <td class="label">Rester connecté</td>
                        <td><input type="checkbox" name="bca-stayIn"></td>
                    </tr>
                    <tr>
                        <td class="label"></td>
                        <td><input type="submit" name="valid" value="Connexion"></td>
                    </tr>
                </table>
            </form>
        </section>';
    }
    ?>

    <section>
        <ul id="retour">
            <li id="return"><a href="index.php">Retour</a></li>
        </ul>
    </section>
</div>

</body>
</html>
