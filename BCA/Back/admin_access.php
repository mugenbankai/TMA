<?php
include('header.php');
require('env.php');

// Traitement de l'ajout d'une règle
if (isset($_POST['add_rule'])) {
    $idModule = $_POST['id_module'];
    $idApprenant = $_POST['id_apprenant'];

    // Récupérer le CodeAcces du module
    $sqlCode = "SELECT CodeAcces FROM Module WHERE id_Module = ?";
    $stmtCode = sqlsrv_query($conn, $sqlCode, array($idModule));

    if ($stmtCode && $row = sqlsrv_fetch_array($stmtCode, SQLSRV_FETCH_ASSOC)) {
        $codeAcces = $row['CodeAcces'];

        // Mettre à jour le NiveauAccess de l'apprenant
        $sqlUpdate = "UPDATE Apprenant SET NiveauAcces = ? WHERE Id_Utilisateur = ?";
        $paramsUpdate = array($codeAcces, $idApprenant);
        $stmtUpdate = sqlsrv_query($conn, $sqlUpdate, $paramsUpdate);

        if ($stmtUpdate === false) {
            echo '<div style="color:red">Erreur de mise à jour: ' . print_r(sqlsrv_errors(), true) . '</div>';
        } else {
            echo '<div style="color:lightgreen">L\'accès "' . $codeAcces . '" a été accordé avec succès.</div>';
        }
    } else {
        echo '<div style="color:red">Erreur : Module introuvable.</div>';
    }
}

// Traitement de la suppression d'une règle
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM RegleAcces WHERE id_Regle = ?";
    $stmt = sqlsrv_query($conn, $sql, array($id));
}
?>

<h1>Gestion des accès</h1>

<h2>Ajouter un accès à un apprenant</h2>
<form method="post">
    <label>Module à accorder :</label><br>
    <select name="id_module" required>
        <option value="">-- Sélectionner un module --</option>
        <?php
        $sqlModules = "SELECT id_Module, Nom FROM Module";
        $stmtModules = sqlsrv_query($conn, $sqlModules);
        while ($row = sqlsrv_fetch_array($stmtModules, SQLSRV_FETCH_ASSOC)) {
            echo '<option value="' . $row['id_Module'] . '">' . htmlspecialchars($row['Nom']) . '</option>';
        }
        ?>
    </select><br><br>

    <label>ID de l'apprenant :</label><br>
    <input type="number" name="id_apprenant" required><br><br>

    <input type="submit" name="add_rule" value="Accorder l'accès">
</form>

<hr>

<h2>Liste des apprenants avec leur niveau d'accès</h2>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Niveau d'accès</th>
    </tr>
    <?php
    $sql = "SELECT Id_Utilisateur, Email, NiveauAcces FROM Apprenant";
    $stmt = sqlsrv_query($conn, $sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td>' . $row['Id_Utilisateur'] . '</td>';
        echo '<td>' . htmlspecialchars($row['Email']) . '</td>';
        echo '<td>' . htmlspecialchars($row['NiveauAcces']) . '</td>';
        echo '</tr>';
    }
    ?>
</table>

<p><a href="index.php">Retour</a></p>
</body>

</html>