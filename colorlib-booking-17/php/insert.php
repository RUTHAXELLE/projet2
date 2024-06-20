<?php
include 'connect.php';

// Données du formulaire
$chronoCil = $_POST['chronoCil'];
$chassis = $_POST['chassis'];
$noms = $_POST['noms'];
$tel = $_POST['tel'];
$image1 = $_FILES['image1']['name'];
$image2 = $_FILES['image2']['name'];
$image3 = $_FILES['image3']['name'];

// Renommage des images avec le champ Chronocil
$image1_nom = $chronoCil . "_image1_" . time() . "." . pathinfo($image1, PATHINFO_EXTENSION);
$image2_nom = $chronoCil . "_image2_" . time() . "." . pathinfo($image2, PATHINFO_EXTENSION);
$image3_nom = $chronoCil . "_image3_" . time() . "." . pathinfo($image3, PATHINFO_EXTENSION);

// Répertoire de stockage des images
$repertoire = "dossiers/" . $chronoCil . "/";
if (!file_exists($repertoire)) {
    mkdir($repertoire, 0777, true);
}

// Déplacement et renommage des images
move_uploaded_file($_FILES["image1"]["tmp_name"], $repertoire . $image1_nom);
move_uploaded_file($_FILES["image2"]["tmp_name"], $repertoire . $image2_nom);
move_uploaded_file($_FILES["image3"]["tmp_name"], $repertoire . $image3_nom);

// Recherche de l'élément correspondant dans la première table
$sql_select = "SELECT * FROM rejetbase WHERE chronoCil = '$chronoCil'";
$result_select = $conn->query($sql_select);

if ($result_select->num_rows > 0) {
    // L'élément est trouvé dans la première table
    $row = $result_select->fetch_assoc();
    $motif = $row['motif'];
    $operateur = $row['operateur'];

    
    


    // Requête préparée pour l'insertion des données
$sql_insert = "INSERT INTO form (chronoCil, chassis, noms, tel, image1, image2, image3, motif, operateur, statut) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Dossier Soumis')";

// Préparation de la requête
$stmt = $conn->prepare($sql_insert);

// Liaison des paramètres
$stmt->bind_param("sssssssss", $chronoCil, $chassis, $noms, $tel, $image1, $image2, $image3, $motif, $operateur);

// Exécution de la requête
if ($stmt->execute()) {
    echo "Formulaire envoyé avec succès !";
} else {
    echo "Erreur lors de l'insertion, veuillez réessayer s'il vous plaît : " . $stmt->error;
}
    // Insérer les données dans la deuxième table
    //$sql_insert = "INSERT INTO form (chronoCil, chassis, noms, tel, image1, image2, image3, motif, operateur) VALUES ('$chronoCil', '$chassis', '$noms', '$tel', '$image1', '$image2', '$image3', '$motif', '$operateur')";
    
    //if ($conn->query($sql_insert) === TRUE) {
        //echo "Formulaire envoyé avec succès !";
        
        
    //} else {
       // echo "Erreur lors de l'insertion, veuillez réessayé s'il vous plais : " . $conn->error;
   // }
//} else {
    //echo "Aucun ChronoCil trouvé, veuillez entrer un chronoCil rejeté.";
    $stmt->close();
}

header("Location: pagethnk.php");
    exit; 

?>