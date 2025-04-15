<?php

// TODO: Afficher un listing des fichiers dans le dossier [numDuParcours][numDuChallenge]

$work[0][2] = 'Techniques de base de l’apprentissage de l’académie';
$work[2][4] = 'Méthode orientale & méthode occidentale de l’apprentissage';

include('header.php');

$isConnected = (isset($_COOKIE['mail']) || isset($_SESSION['mail'])) ? true : false;

if ($isConnected) {
  include('bcaAccessCodeSystem.php');
}

$course = 'course' . $_GET['course'];
$challenge = 'rank' . $_GET['challenge'];

echo '<h2>Description du travail</h2><strong>
			Parcours actuel</strong> : ' . $$course . '<br>';
echo '<strong>Challenge visé</strong> : ' . $$challenge . '<br>';
echo '<strong>Défi demandé</strong> : <u>' . $work[$_GET['course']][$_GET['challenge']] . '</u><br><br>';

?>

<h2>Upload du travail</h2>

<form action="bcaWorkUpload-validation.php?course=<?= $_GET['course'] ?>&challenge=<?= $_GET['challenge'] ?>"
  method="post" enctype="multipart/form-data">
  <h1 style="margin-bottom: 50px;">Selectionnez un fichier à uploader:</h1>
  <input type="file" name="fileToUpload" id="fileToUpload" multiple>
  <input type="hidden" name="course" value="<?= $_GET['course'] ?>">
  <input type="hidden" name="challenge" value="<?= $_GET['challenge'] ?>">
  <input type="submit" value="Upload" name="submit">
</form>

<br>
<a href="index.php">Retour</a>

</body>

</html>