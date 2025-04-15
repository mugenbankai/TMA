<?php

if(isset($_POST["valid"])){
	if(isset($_POST["bca-mail"]) && $_POST["bca-mail"]!=''){
		require_once('env.php');
        $mail = $_POST["bca-mail"];
		$selectall = $db->query('SELECT * FROM user WHERE mail="'.$mail.'"');
        $result = $selectall->fetch();
        $counttable = count((is_countable($result)?$result:[]));
        if($counttable >= 1){
            // envoyer mail @ $mail avec $result['password'] en token

            $subject = 'Bonsai Coach Academy: renouvellement de mot de passe';
            $pwd = password_hash($result['password'],PASSWORD_DEFAULT);
            $url = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST);
            $message = '<html>
                            <body>
                                <p>Bonjour,</p>
                                <p>Une demande de changement de mot de passe à été faite sur le site de la Bonsai Coach Academy. Pour changer votre mot de passe, <a href="https://'.$url.'/bca/userNewPassword.php?mail='.$mail.'&token='.$pwd.'">veuillez cliquer ici</a>.</p>
                                <p>&Agrave; bient&ocirc;t !<br>
                                L\'association "Bonsai, la part du colibri".</p>
                            </body>
                        </html>';

            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=iso-8859-1';
            $headers[] = 'Reply-To: Pascal <contact@bonsailapartducolibri.org>';
            $headers[] = 'From: Pascal <contact@bonsailapartducolibri.org>';

            if(mail($mail, $subject, $message, implode("\r\n", $headers))){
                $return = 'Nous vous avons envoyé un mail à cette adresse pour renouveller votre mot de passe';	
            }
            
            // if(password_verify($_POST["bca-pwd"],$result['password'])){
            //     $return = "Connexion réussie";
            //     // envoyer cookies ou session
            //     if(isset($_POST['bca-stayIn'])){
            //         $expire = 365*24*3600; // on définit la durée du cookie, 1 an
            //         setcookie("mail",$mail,time()+$expire);  // on l'envoi
            //     }
            //     else{
            //         session_start();
            //         $_SESSION['mail'] = $mail;
            //         echo 'ok'.$_SESSION['mail'];
            //     }
            // }
            // else{
            //     $return = '<span style="color:red">Mauvais mot de passe, <a href="userPasswordReset.php">réinitialisation du mot de passe</a>.</span>';
            // }
        }
        else{
            $return = '<span style="color:red">Pas de mail correspondant</span>';
        }
    }
	else{
		$return = '<span style="color:red">Veuillez préciser un mail</span>';
	}
}
else{
	$return = '<span style="color:red">Formulaire non validé</span>';
}

?>

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

        <h1><?php echo $return ?></h1>
    </header>

    <section>
        <ul id="retour">
            <li id="return">
                <a href="index.php">Retour</a></li>
        </ul>
    </section>

</div>
    
</body>
</html>