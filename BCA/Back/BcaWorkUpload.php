<?php

include('header.php');

$isConnected = isset($_COOKIE['mail']) || isset($_SESSION['mail']);
if (!$isConnected) {
  echo "Vous devez être connecté.<br><a href='index.php'>Retour</a>";
  exit;
}

require('env.php'); // Connexion BDD
require_once('bcaAccessCodeSystem.php'); // Fichier où se trouve la fonction peutAccederModule()

$mail = $_COOKIE['mail'] ?? $_SESSION['mail'] ?? null;
$idUser = getIdUser($mail); // Fonction que tu utilises déjà

$course = $_GET['course'];
$challenge = $_GET['challenge'];

// ⚠️ À adapter : suppose que chaque combinaison course/challenge a un module unique


$idModule = getModuleIdFromCourseChallenge($course, $challenge, $conn);

// Débogage : affiche les informations sur le module et l'utilisateur
// (À retirer en production)
// echo "<pre style='color:yellow'>";
// echo "DEBUG MODULE: course=$course challenge=$challenge<br>";
// echo "Module ID: " . ($idModule ?? 'null') . "<br>";

// if ($idModule) {
//   $stmt = sqlsrv_query($conn, "SELECT CodeAcces FROM Module WHERE id_Module = ?", array($idModule));
//   $mod = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
//   echo "CodeAcces du module : " . ($mod['CodeAcces'] ?? 'non trouvé') . "<br>";
// }

// echo "Utilisateur ID: $idUser<br>";

// $stmt2 = sqlsrv_query($conn, "SELECT NiveauAcces FROM Apprenant WHERE Id_Utilisateur = ?", array($idUser));
// $user = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
// echo "NiveauAcces de l'utilisateur : " . ($user['NiveauAcces'] ?? 'non trouvé') . "<br>";
// echo "</pre>";


// 🔒 Vérifie si l'utilisateur a le droit d'accéder à ce module
if (!$idModule || !peutAccederModule($idModule, $idUser, $conn)) {
  echo "<p style='color:red'>🚫 Accès refusé : vous n'avez pas encore débloqué ce module.</p>";
  echo '<a href="index.php">Retour</a>';
  exit;
}

// ---------- CONTENU DE LA PAGE (si accès autorisé) ----------------

$courseLabel = 'course' . $course;
$challengeLabel = 'rank' . $challenge;

// Contenu du défi
$work[0][0] = 'Introduction au bonsaï';
$work[0][1] = 'Premiers outils';
$work[0][2] = 'Soins de base';
$work[1][0] = 'Rempotage';
$work[1][1] = 'Taille et ligature';
$work[1][2] = 'Arrosage maîtrisé';
$work[2][0] = 'Techniques avancées';
$work[2][1] = 'Bonsaï en extérieur';
$work[2][2] = 'Préparation aux concours';
// ... (autres lignes inchangées)

// include('bcaAccessCodeSystem.php');

echo '<h2>Description du travail</h2>';
echo '<p><strong>Parcours actuel :</strong> ' . $$courseLabel . '</p>';
echo '<p><strong>Challenge visé :</strong> ' . $$challengeLabel . '</p>';
echo '<p><strong>Défi demandé :</strong> <u>' . $work[$course][$challenge] . '</u></p>';
?>

<h2>Upload du travail</h2>
<form action="bcaWorkUpload-validation.php?course=<?= $course ?>&challenge=<?= $challenge ?>" method="post"
  enctype="multipart/form-data">
  <label for="fileToUpload">Sélectionnez un fichier à uploader :</label><br>
  <input type="file" name="fileToUpload" id="fileToUpload" multiple>
  <input type="hidden" name="course" value="<?= $course ?>">
  <input type="hidden" name="challenge" value="<?= $challenge ?>">
  <br>
  <input type="submit" value="Upload" name="submit">
</form>

<?php
$dir = $idUser . '/' . $course . ' ' . $challenge;
if (is_dir($dir)) {
  $files = scandir($dir);
  echo "<h2>Fichiers déjà uploadés :</h2><ul>";
  foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
      echo '<li><a href="' . $dir . '/' . $file . '" target="_blank">' . $file . '</a></li>';
    }
  }
  echo "</ul>";
} else {
  echo "<p>Aucun fichier n'a encore été uploadé pour ce défi.</p>";
}
?>

</body>

</html>