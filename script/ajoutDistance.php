<?php
    //ajout d'une distance entre deux villes sur la page paramètrage

    session_start();

    $ville1 = $_GET["ville1"];
    $ville2 = $_GET["ville2"];
    $distance = $_GET["distance"];

    //si le code postale de la ville 1 est supérieur à celui de la ville 2 alors on inverse leur valeurs
    //cela permet de toujours placer en première position la ville ayant le cp le plus petit afin de vérifier plus facilement si une distance est déjà renseignée
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

    //si distance déjà renseignée
    if ($ligne = $stmt->fetch()){
        $_SESSION["error"] = "Distance déjà renseignée";
    } else {
        //si la distance n'est pas un nombre
        if(!is_int(intval($distance))){
            $_SESSION["error"] = "La distance renseignée n'est pas valable";
        } else {
            $stmt = $pdo->prepare ("INSERT INTO distance(dis_idVilleDepart, dis_idVilleArrivee, dis_km) VALUES (:ville1, :ville2, :distance)");
            $stmt->bindParam ("ville1", $ville1,PDO::PARAM_INT);
            $stmt->bindParam ("ville2", $ville2,PDO::PARAM_INT);
            $stmt->bindParam ("distance", $distance,PDO::PARAM_INT);
            $stmt->execute ();

            $_SESSION["ajout"] = "Ajout de la distance effectué";
        }
    }

    header('location: ../vues/parametres.php');    
?>
