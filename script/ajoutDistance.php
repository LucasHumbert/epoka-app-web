<?php
    $ville1 = $_GET["ville1"];
    $ville2 = $_GET["ville2"];
    $distance = $_GET["distance"];

    if($ville1 > $ville2){
        $temp = $ville2;
        $ville2 = $ville1;
        $ville1 = $temp;
    }

    $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
    $stmt = $pdo->prepare ("SELECT * FROM distance WHERE dis_idVilleDepart = :ville1 AND dis_idVilleArrivee = :ville2");
    $stmt->bindParam ("ville1", $ville1,PDO::PARAM_INT);
    $stmt->bindParam ("ville2", $ville2,PDO::PARAM_INT);
    $stmt->execute ();

    if ($ligne = $stmt->fetch()){
        die("<center> Cette distance a déjà été renseignée <br />
        <a href='javascript:history.back()' color='#5fa8d3';>Page précédente</a> </center>");
    } else {
        $stmt = $pdo->prepare ("INSERT INTO distance(dis_idVilleDepart, dis_idVilleArrivee, dis_km) VALUES (:ville1, :ville2, :distance)");
        $stmt->bindParam ("ville1", $ville1,PDO::PARAM_INT);
        $stmt->bindParam ("ville2", $ville2,PDO::PARAM_INT);
        $stmt->bindParam ("distance", $distance,PDO::PARAM_INT);
        $stmt->execute ();

        header('location: ../vues/parametres.php');
    }
    
?>