<?php

// Système de code d'accès - optimisé pour SQL Server et moins de requêtes BDD

$rank0 = "Parcours non suivi";
$rank1 = "Parcours suivi";
$rank2 = "Apprenti";
$rank3 = "Compagnon";
$rank4 = "Passeur";
$rank5 = "Guide";

$course0 = "Apprentissage et transmission";
$course1 = "Culture en pot";
$course2 = "Art de l'ouvrage";
$course3 = "Arts Associés";

$mail = isset($_COOKIE['mail']) ? $_COOKIE['mail'] : $_SESSION['mail'];

function getNextChallenge($selectedCourse, $accessCodeArrayed)
{
	return $nextChallengeNumber = (int) $accessCodeArrayed[$selectedCourse] + 1;

}

function isAuthorizedToJoin($NumberOfTheCourse, $accessCodeArrayed)
{
	switch ($NumberOfTheCourse) {
		case 0:
			return $accessCodeArrayed[0] == 0;
		case 1:
			return $accessCodeArrayed[1] >= 2;
		case 2:
		case 3:
			return $accessCodeArrayed[0] >= 2;
		default:
			return false;
	}
}

function isJoined($NumberOfTheCourse, $accessCodeArrayed)
{
	return $accessCodeArrayed[$NumberOfTheCourse] >= 1;
}

function displayCourseLink($NumberOfTheCourse, $accessCodeArrayed)
{
	if (isAuthorizedToJoin($NumberOfTheCourse, $accessCodeArrayed)) {
		return ' <form method="POST" name="course' . $NumberOfTheCourse . '">
                    <input type="hidden" name="course" value="' . $NumberOfTheCourse . '">
                    <a class="noUnderline" href="#" onclick="javascript:document.course' . $NumberOfTheCourse . '.submit();">
                        <i class="fas fa-shoe-prints"></i> Joindre
                    </a>
                </form>';
	} elseif (isJoined($NumberOfTheCourse, $accessCodeArrayed)) {
		return ' <a class="noUnderline continue" href="bcaWorkUpload.php?course=' . $NumberOfTheCourse . '&challenge=' . getNextChallenge($NumberOfTheCourse, $accessCodeArrayed) . '">
                    <i class="fas fa-arrow-right"></i> Continuer
                </a>';
	} else {
		return ' <a href="#"><i class="fas fa-info-circle" title="Vous devez compléter d\'autres défis avant de commencer ce parcours"></i></a>';
	}
}

function setToOneNewJoinedCourse($NumberOfJoinedCourse, $accessCodeArrayed)
{
	$accessCodeArrayed[$NumberOfJoinedCourse] = 1;
	$accessCodeForDB = arrayToStringAccessCode($accessCodeArrayed);

	require('env.php');
	global $mail;

	$sql = "UPDATE Apprenant SET AccessCode = ? WHERE Email = ?";
	$params = array($accessCodeForDB, $mail);
	$stmt = sqlsrv_query($conn, $sql, $params);

	if ($stmt === false) {
		die(print_r(sqlsrv_errors(), true));
	}

	header("Refresh:0");
	exit();
}

function getAccessCodeFromDB()
{
	require('env.php');
	global $mail;

	$sql = "SELECT AccessCode FROM Apprenant WHERE Email = ?";
	$params = array($mail);
	$stmt = sqlsrv_query($conn, $sql, $params);

	if ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
		return $row['AccessCode'];
	} else {
		return 'erreur: no accessCode';
	}
}

function stringToArrayAccessCode($accessCodeFromDB)
{
	$array = explode(' ', $accessCodeFromDB);
	// Compléter les cases manquantes avec 0 si besoin
	for ($i = 0; $i < 4; $i++) {
		if (!isset($array[$i])) {
			$array[$i] = 0;
		}
	}
	return $array;
}


function arrayToStringAccessCode($accessCodeForDB)
{
	return implode(' ', $accessCodeForDB);
}

function numberToRankNamed($numberFromArray)
{
	global $rank0, $rank1, $rank2, $rank3, $rank4, $rank5;

	switch ($numberFromArray) {
		case 0:
			return '<span class="disabled">' . $rank0 . '</span>';
		case 1:
			return '<span class="enabled">' . $rank1 . '</span>';
		case 2:
			return '<i class="fa fa-graduation-cap"></i> <span class="' . $rank2 . '">' . $rank2 . '</span>';
		case 3:
			return '<i class="fa fa-handshake"></i> <span class="' . $rank3 . '">' . $rank3 . '</span>';
		case 4:
			return '<i class="fa fa-hand-holding"></i> <span class="' . $rank4 . '">' . $rank4 . '</span>';
		case 5:
			return '<i class="fa fa-star"></i> <span class="' . $rank5 . '">' . $rank5 . '</span>';
		default:
			return '';
	}
}

function displayCoursesList($accessCodeArrayed)
{
	global $course0, $course1, $course2, $course3;

	echo '<ul id="parcours-list" class="text-light d-flex flex-column">';
	echo '<li class="gap-5"><strong>' . $course0 . ' : </strong>' . numberToRankNamed($accessCodeArrayed[0]) . displayCourseLink(0, $accessCodeArrayed) . ' <span class="see"><i class="fas fa-eye"></i> Voir</span></li>';
	echo '<li class="gap-5"><strong>' . $course1 . ' : </strong>' . numberToRankNamed($accessCodeArrayed[1]) . displayCourseLink(1, $accessCodeArrayed) . ' <span class="see"><i class="fas fa-eye"></i> Voir</span></li>';
	echo '<li class="gap-5"><strong>' . $course2 . ' : </strong>' . numberToRankNamed($accessCodeArrayed[2]) . displayCourseLink(2, $accessCodeArrayed) . ' <span class="see"><i class="fas fa-eye"></i> Voir</span></li>';
	echo '<li class="gap-5"><strong>' . $course3 . ' : </strong>' . numberToRankNamed($accessCodeArrayed[3]) . displayCourseLink(3, $accessCodeArrayed) . ' <span class="see"><i class="fas fa-eye"></i> Voir</span></li>';
	echo '</ul>';
}
?>