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

function getNextChallenge($rank)
{
	// Si par exemple le niveau actuel est 2, on vise le 3
	return is_numeric($rank) ? intval($rank) + 1 : 0;
}

function getIdUser($email)
{
	require('env.php');
	$sql = "SELECT Id_Utilisateur FROM Apprenant WHERE Email = ?";
	$params = array($email);
	$stmt = sqlsrv_query($conn, $sql, $params);

	if ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
		return $row['Id_Utilisateur'];
	} else {
		return null;
	}
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

function peutAccederModule($idModule, $idApprenant, $conn)
{
	// 1. Récupérer le CodeAcces du module
	$sqlModule = "SELECT CodeAcces FROM Module WHERE id_Module = ?";
	$stmtModule = sqlsrv_query($conn, $sqlModule, array($idModule));
	if (!$stmtModule || !sqlsrv_has_rows($stmtModule))
		return false;

	$rowModule = sqlsrv_fetch_array($stmtModule, SQLSRV_FETCH_ASSOC);
	$codeModule = $rowModule['CodeAcces'];

	// 2. Récupérer le NiveauAcces de l'utilisateur
	$sqlUser = "SELECT NiveauAcces FROM Apprenant WHERE Id_Utilisateur = ?";
	$stmtUser = sqlsrv_query($conn, $sqlUser, array($idApprenant));
	if (!$stmtUser || !sqlsrv_has_rows($stmtUser))
		return false;

	$rowUser = sqlsrv_fetch_array($stmtUser, SQLSRV_FETCH_ASSOC);
	$niveauUser = $rowUser['NiveauAcces'];

	// 3. Comparaison directe
	return $niveauUser === $codeModule;
}



function getModuleIdFromCourseChallenge($course, $challenge, $conn)
{
	$sql = "SELECT id_Module FROM Module WHERE Nom LIKE ?";
	$nomModule = "Module $course-$challenge :%"; // Le % permet de matcher tout ce qui suit
	$params = array($nomModule);
	$stmt = sqlsrv_query($conn, $sql, $params);

	if ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
		return $row['id_Module'];
	}

	return null;
}


function displayCourseLink($NumberOfTheCourse, $accessCodeArrayed)
{
	$nextChallenge = getNextChallenge($accessCodeArrayed[$NumberOfTheCourse]);
	$url = 'bcaWorkUpload.php?course=' . $NumberOfTheCourse . '&challenge=' . $nextChallenge;

	return '<a href="' . $url . '" class="btn btn-sm btn-outline-success">Continuer</a>';
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

	$courses = [$course0, $course1, $course2, $course3];

	echo '<ul id="parcours-list" class="text-light d-flex flex-column gap-4" style="list-style: none; padding: 0;">';

	foreach ($courses as $index => $courseName) {
		$rank = numberToRankNamed($accessCodeArrayed[$index]);
		$link = displayCourseLink($index, $accessCodeArrayed);

		echo '<li class="d-flex justify-content-between align-items-center p-3 border rounded bg-dark-subtle shadow-sm">';
		echo '<div><strong>' . htmlspecialchars($courseName) . ' :</strong> ' . $rank . '</div>';
		echo '<div class="d-flex align-items-center gap-2">' . $link . '<span class="see text-primary ms-3" style="cursor:pointer;"><i class="fas fa-eye"></i> Voir</span></div>';
		echo '</li>';
	}

	echo '</ul>';
}

?>