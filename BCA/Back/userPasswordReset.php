<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<title>Bonsai Coach Academie</title>
	<meta name="description" content="Plateforme de peer-learning pour l'art du bonsaï">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
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

        <h1>Connexion</h1>
    </header>

    <section>
        <form action="userPasswordReset-validation.php" method="post">
            <table>
                <tr>
                    <td class="label">Mail</td>
                    <td><input type="email" name="bca-mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"><br></td>
                </tr>
                <tr>
                    <td class="label"></td>
                    <td><input type="submit" name="valid" value="Demander le changement de mon mot de passe"></td>
                </tr>
            </table>
        </form>
    </section>

    <section>
        <ul id="retour">
            <li id="return">
                <a href="index.php">Retour</a></li>
        </ul>
    </section>

</div>
    
</body>
</html>