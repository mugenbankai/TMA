<?php

/*
TODO:

Completer isJoined avec isJoinable qui contiendra en plus les regles d'acces au challenge

Section Correction: "Travaux que je peux corriger" > Affichages Rendus >=2 > Selection > Affichage rendu > Etes-vous sûr ? > BDD
Section Mes travaux: if !isset(statut1) > Non corrigé, else > En cours / if !isset(statut3) if(total >=2) validé > obtenir mon badge, sinon non validé > Recommencer

Section "Obtenir mon badge" quand validé par triumvirat Ou valider le badge quand 3eme statut ajouté

*/

include('header.php');

$isConnected = (isset($_COOKIE['mail']) || isset($_SESSION['mail'])) ? true : false;

if ($isConnected) {
	include('bcaAccessCodeSystem.php');

	$accessCode = getAccessCodeFromDB();
	$accessCodeArrayed = stringToArrayAccessCode($accessCode);

	if (isset($_POST['course'])) {
		setToOneNewJoinedCourse($_POST['course'], $accessCodeArrayed);
	}
}

?>

<div class="d-flex flex-column gap-5 mt-5">
	<div>
		<h1 class="mb-5">Bonsai Coach Academie</h1>
		<p class="text-light">Plateforme d'apprentissage de <em>l'art du bonsaï</em></p>
	</div>
	<section class="d-flex flex-column">
		<h1 id="community" class="fs-1 mb-5">Mes parcours & badges</h1>
		<?php
		if ($isConnected) {
			displayCoursesList($accessCodeArrayed);
		}

		?>
	</section>

	<section>
		<h1 class="fs-2 mb-5" id="courses">Badges disponibles</h1>
		<ul id="badges-list" class="text-light">
			<li><i class="fa fa-graduation-cap"></i> Apprenti</li>
			<li><i class="fa fa-handshake"></i> Compagnon</li>
			<li><i class="fa fa-hand-holding"></i> Passeur</li>
			<li><i class="fa fa-star"></i> Guide</li>
		</ul>
		<p id="copyright">Copyright 2024 © EPSI Lille</p>
	</section>
</div>


</body>

</html>