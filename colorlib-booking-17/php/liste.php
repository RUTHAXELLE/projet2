<?php
include 'connect.php';


// Si le formulaire de validation est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['valider'])) {
    $id_dossier = $_POST['id_dossier'];

    // Mettre à jour le statut du dossier dans la base de données
    $sql_update = "UPDATE dossiers SET statut = 'validé', date_validation = NOW() WHERE id = $id_dossier";

    if ($conn->query($sql_update) === TRUE) {
        echo "Statut du dossier mis à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour du statut du dossier: " . $conn->error;
    }
}

// Récupérer les dossiers depuis la base de données
$sql_select = "SELECT chronoCil, chassis, statut FROM form";
$result = $conn->query($sql_select);

if ($result->num_rows > 0) {
    // Afficher les dossiers sous forme de tableau
    echo "<table border='1'>
    <tr>
    <th>chronoCil</th>
    <th>chassis</th>
    <th>Statut</th>
    
    </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["chronoCil"] . "</td>";
        echo "<td>" . $row["chassis"] . "</td>";
        echo "<td>" . $row["statut"] . "</td>";
        //echo "<td>";

        // Afficher le bouton en fonction du statut du dossier
        if ($row["statut"] == "en attente de validation") {
            echo "<form method='post'>";
            echo "<input type='hidden' name='id_dossier' value='" . $row["chronoCil"] . "'>";
            echo "<button  type='button' name='valider' value='Valider'> Click Me! </button>";
            echo "</form>";

        } elseif ($row["statut"] == "validé") {
            $row["statut"] ="NON validé";
        }else{$row["statut"] ="oui validé";

        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Aucun dossier trouvé dans la base de données.";
}

$conn->close();
?>
