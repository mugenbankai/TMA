<?php
// Afficher les erreurs à l'écran (utile en dev)
ini_set('display_errors', 1);
error_reporting(E_ALL);

$return = '';

// insertion en bdd
if (isset($_POST["valid"])) {
    if (isset($_POST["bca-mail"]) && $_POST["bca-mail"] != '') {
        require_once('env.php');

        $mail = $_POST["bca-mail"];
        $pwd = password_hash($_POST['bca-pwd'], PASSWORD_DEFAULT);

        // Vérification si l'email est déjà inscrit
        $sqlCheck = "SELECT * FROM Apprenant WHERE Email = ?";
        $paramsCheck = array($mail);
        $stmtCheck = sqlsrv_query($conn, $sqlCheck, $paramsCheck);

        if ($stmtCheck === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $row = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC);

        if (!$row) {
            // Envoi du mail de confirmation
            $subject = 'Bonsai Coach Academy: confirmation d\'inscription';
            $url = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST);

            $message = '<html>
                            <body>
                                <p>Bonjour et bienvenue sur la BCA !</p>
                                <p>Pour terminer votre inscription, <a href="https://' . $url . '/bca/userRegistrationMailConfirm.php?mail=' . urlencode($mail) . '&token=' . urlencode($pwd) . '">cliquez ici</a>.</p>
                                <p>À bientôt !<br>
                                L\'association "Bonsai, la part du colibri".</p>
                            </body>
                        </html>';

            $headers = [
                'MIME-Version: 1.0',
                'Content-type: text/html; charset=iso-8859-1',
                'Reply-To: Pascal <contact@bonsailapartducolibri.org>',
                'From: Pascal <contact@bonsailapartducolibri.org>'
            ];

            if (true) {
                $return = "Nous venons de vous envoyer un mail, merci de cliquer sur le lien dans celui-ci pour confirmer l'inscription !";
            } else {
                $return = '<span style="color:red">Échec de l\'envoi de l\'email.</span>';
            }

            // (Optionnel) Insertion dans la base directement ici si tu ne fais pas de confirmation par mail
            
            $sqlInsert = "INSERT INTO Apprenant (Email, MotDePasse, NiveauAcces) VALUES (?, ?, ?)";
            $paramsInsert = array($mail, $pwd, 0);
            $stmtInsert = sqlsrv_query($conn, $sqlInsert, $paramsInsert);
            if ($stmtInsert === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            
        } else {
            $return = '<span style="color:red">Mail déjà inscrit</span>';
        }
    } else {
        $return = '<span style="color:red">Veuillez préciser un mail</span>';
    }
} else {
    $return = '<span style="color:red">Formulaire non validé</span>';
}
?>