<?php
    $ville1 = $_GET["ville1"];
    $ville2 = $_GET["ville2"];
    $distance = $_GET["distance"];

    $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
    $stmt = $pdo->prepare ("INSERT INTO distance(dis_idVilleDepart, dis_VilleArrivee, dis_km) VALUES (:ville1, :ville2, :distance)");
    $stmt->bindParam ("ville1", $ville1,PDO::PARAM_INT);
    $stmt->bindParam ("ville2", $ville2,PDO::PARAM_INT);
    $stmt->bindParam ("distance", $distance,PDO::PARAM_INT);
    $stmt->execute ();

    header('location: ../vues/parametres.php');
?>