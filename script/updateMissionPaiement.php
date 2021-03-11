<?php
        $id = $_GET["id"];

        $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
        $stmt = $pdo->prepare ("UPDATE mission SET mis_paiement = 1 WHERE mis_id = :id");
        $stmt->bindParam ("id", $id,PDO::PARAM_STR);
        $stmt->execute ();

        header('location: ../vues/paiement.php');
?>