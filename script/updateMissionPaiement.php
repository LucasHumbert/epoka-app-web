<?php
        $id = $_GET["id"];
        $montant = floatval($_GET["montant"]);
        
        $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
        $stmt = $pdo->prepare ("UPDATE mission SET mis_paiement = 1, mis_montant = :montant WHERE mis_id = :id");
        $stmt->bindParam ("id", $id,PDO::PARAM_INT);
        $stmt->bindParam ("montant", $montant,PDO::PARAM_STR);
        $stmt->execute ();

        header('location: ../vues/paiement.php');
?>