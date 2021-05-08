<?php

    //script qui change les paramètres qui permettront de calculer le montant des missions

    session_start();
    
    $prixKm = $_GET['remboursement'];
    $prixJournee = $_GET['indemnite'];

    //vérification des valeurs
    if(!is_numeric($prixKm)){
        $_SESSION["error"] = "Remboursement au Km invalide";
        die(header('location: ../vues/parametres.php'));
    }

    if(!is_int(intval($prixJournee))){
        $_SESSION["error"] = "Indemnité d'hébergement invalide";
        die(header('location: ../vues/parametres.php'));
    }

    $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
    $stmt = $pdo->prepare ("UPDATE param SET prixKm = :prixKm, prixJournee = :prixJournee");
    $stmt->bindParam ("prixKm", $prixKm,PDO::PARAM_STR);
    $stmt->bindParam ("prixJournee", $prixJournee,PDO::PARAM_STR);
    $stmt->execute ();

    $_SESSION["ajout"] = "Modifications effectuées";
    header('location: ../vues/parametres.php');
?>