<?php
        $dateDebut = $_GET['dateDebut'];
        $dateFin = $_GET['dateFin'];
        $destination = $_GET['dest'];
        $salarie = $_GET['salarie'];

        $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
        $stmt = $pdo->prepare ("INSERT INTO mission(`mis_dateDebut`, `mis_dateFin`, `mis_idDestination`, `mis_idSalarie`, `mis_validation`, `mis_paiement`) 
                                VALUES (:dateDebut, :dateFin, :destination, :salarie, 0, 0)");
        $stmt->bindParam ("dateDebut", $dateDebut,PDO::PARAM_STR);
        $stmt->bindParam ("dateFin", $dateFin,PDO::PARAM_STR);
        $stmt->bindParam ("destination", $destination,PDO::PARAM_INT);
        $stmt->bindParam ("salarie", $salarie,PDO::PARAM_INT);
        $stmt->execute ();
?>