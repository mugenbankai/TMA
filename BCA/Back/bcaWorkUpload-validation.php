<?php

include('header.php');

// Vérifie la connexion
$isConnected = (isset($_COOKIE['mail']) || isset($_SESSION['mail'])) ? true : false;
if ($isConnected) {
  $mail = isset($_COOKIE['mail']) ? $_COOKIE['mail'] : $_SESSION['mail'];
} else {
  echo 'Vous n\'êtes pas connecté, veuillez vous inscrire ou vous connecter sur la page d\'accueil<br><a href="index.php">Retour</a>';
  exit();
}

// Fonction pour récupérer l'ID utilisateur depuis SQL Server
function getIdUser($mail)
{
  require('env.php');
  $sql = "SELECT Id_Utilisateur FROM Apprenant WHERE Email = ?";
  $params = array($mail);
  $stmt = sqlsrv_query($conn, $sql, $params);

  if ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
    return $row['Id_Utilisateur'];
  } else {
    return 'erreur req';
  }
}

$idUser = getIdUser($mail);

if ($idUser === 'erreur req') {
  echo "Erreur lors de la récupération de l'utilisateur.";
  exit();
}

// Création des dossiers
if (!is_dir($idUser)) {
  mkdir($idUser, 0777);
}

$nameOfDirForWork = $_GET['course'] . ' ' . $_GET['challenge'];
$target_dir = $idUser . '/' . $nameOfDirForWork;

if (!is_dir($target_dir)) {
  mkdir($target_dir, 0777);
}

// Upload du fichier
$target_file = $target_dir . '/' . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$fileExtension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

if (isset($_POST["submit"])) {

  // Vérifie si le fichier existe déjà
  if (file_exists($target_file)) {
    echo "Désolé, le fichier existe déjà.";
    $uploadOk = 0;
  }

  // Vérifie la taille
  if ($_FILES["fileToUpload"]["size"] > 5 * 1024 * 1024) { // 5 Mo
    echo "Désolé, votre fichier est trop gros.";
    $uploadOk = 0;
  }

  // Extensions autorisées
  $allowed = ["jpg", "jpeg", "png", "gif", "pdf", "ppt", "pptx"];
  if (!in_array($fileExtension, $allowed)) {
    echo "Désolé, seuls les fichiers JPG, JPEG, PNG, GIF, PDF, PPT & PPTX sont autorisés.";
    $uploadOk = 0;
  }

  if ($uploadOk == 0) {
    echo " Votre fichier n'a pas été uploadé.";
  } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      echo "Le fichier " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " a été uploadé.";
    } else {
      echo "Désolé, une erreur est survenue lors de l'upload.";
    }
  }
}
?>

<br>
<a href="index.php">Retour</a>