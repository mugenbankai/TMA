<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Bonsai Coach Academie</title>
    <meta name="description" content="Plateforme de peer-learning pour l'art du bonsaï">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/b30f5d3ef8.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 600px;
            background-color: #1e1e1e;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.6);
        }

        h1 {
            text-align: center;
            color: #6cb4ff;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
        }

        .label {
            width: 40%;
            padding-right: 10px;
            vertical-align: middle;
        }

        input[type="email"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 6px;
            background-color: #2a2a2a;
            color: #fff;
        }

        input[type="submit"] {
            background-color: #6cb4ff;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #5298dd;
        }

        #retour {
            text-align: center;
            margin-top: 20px;
            list-style: none;
            padding: 0;
        }

        #retour a {
            color: #6cb4ff;
            text-decoration: none;
        }

        #retour a:hover {
            text-decoration: underline;
        }

        nav ul {
            list-style: none;
            padding: 0;
            text-align: right;
        }

        nav a {
            color: #6cb4ff;
            text-decoration: none;
        }

        nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <header>
            <nav>
                <ul id="connection">
                    <li id="signin">
                        <a href="userConnectionForm.php"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                    </li>
                </ul>
            </nav>
            <div class="cleared"></div>

            <h1>Inscription</h1>
        </header>

        <section>
            <form action="userRegistrationForm-validation.php" method="post">
                <table>
                    <tr>
                        <td class="label">Mail</td>
                        <td><input type="email" name="bca-mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                                required></td>
                    </tr>
                    <tr>
                        <td class="label">Mot de passe</td>
                        <td><input type="password" name="bca-pwd" pattern=".{8,}" required></td>
                    </tr>
                    <tr>
                        <td class="label"></td>
                        <td><input type="submit" name="valid" value="S'inscrire"></td>
                    </tr>
                </table>
            </form>
        </section>

        <section>
            <ul id="retour">
                <li id="return">
                    <a href="index.php">⬅ Retour</a>
                </li>
            </ul>
        </section>
    </div>

</body>

</html>