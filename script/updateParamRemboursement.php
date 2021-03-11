<?php
    $prixKm = $_GET['remboursement'];
    $prixJournee = $_GET['indemnite'];

    $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
    $stmt = $pdo->prepare ("UPDATE param SET prixKm = :prixKm, prixJournee = :prixJournee");
    $stmt->bindParam ("prixKm", $prixKm,PDO::PARAM_STR);
    $stmt->bindParam ("prixJournee", $prixJournee,PDO::PARAM_STR);
    $stmt->execute ();

    header('location: ../vues/parametres.php');
?>