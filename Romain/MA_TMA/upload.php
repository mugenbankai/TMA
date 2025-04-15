<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $uploadDir = "uploads/";

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $file = $_FILES["file"];
    $fileName = basename($file["name"]);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    $allowedFormats = ["pdf"];
    if (!in_array($fileType, $allowedFormats)) {
        die("❌ Format non autorisé. Formats acceptés : pdf.");
    }

    if ($file["error"] !== 0) {
        die("❌ Erreur lors de l’upload. Code erreur : " . $file["error"]);
    }

    if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        echo "✅ Fichier uploadé avec succès : <strong>" . $fileName . "</strong>";
    } else {
        echo "❌ Erreur lors du déplacement du fichier.";
    }
} else {
    echo "❌ Aucun fichier reçu.";
}
?>