<?php
// Dossier où les fichiers seront sauvegardés
$target_dir = "uploads/";

// Crée le dossier si il n'existe pas déjà
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Vérifie si un fichier a bien été envoyé
if (!isset($_FILES['file']) || $_FILES['file']['error'] === UPLOAD_ERR_NO_FILE) {
    header('Location: index.html?error=3');
    exit();
}

$file = $_FILES['file'];

// Vérifie s'il n'y a pas d'erreur
if ($file['error'] !== UPLOAD_ERR_OK) {
    header('Location: index.html?error=3');
    exit();
}

// Vérification de l'extension
$allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
$file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($file_extension, $allowed_extensions)) {
    header('Location: index.html?error=1');
    exit();
}

// Vérifie la taille du fichier (max 5 Mo)
if ($file['size'] > 5 * 1024 * 1024) { // 5MB
    header('Location: index.html?error=2');
    exit();
}

// Génère un nom unique pour éviter les conflits
$unique_name = uniqid() . '.' . $file_extension;
$target_file = $target_dir . $unique_name;

// Déplace le fichier téléchargé dans le dossier cible
if (move_uploaded_file($file['tmp_name'], $target_file)) {
    echo "Le fichier a été téléchargé avec succès.";
} else {
    header('Location: index.html?error=3');
    exit();
}
?>