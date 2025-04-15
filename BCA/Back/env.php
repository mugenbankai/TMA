<?php
$serverName = "SHADOW\\SQLEXPRESS";
$connectionOptions = array(
    "Database" => "TMA",
    "UID" => "admintdf",
    "PWD" => "admintdf1234",
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

// Afficher un message uniquement si ce fichier est exécuté directement
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    echo "Connexion réussie à la base de données!";
}
?>