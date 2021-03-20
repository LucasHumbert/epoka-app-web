<?php
    
    if (!isset($_GET["user"])) die ("#User absent");
    $user = $_GET["user"];
    if (!isset($_GET["mdp"])) die ("#Mdp absent");
    $mdp = $_GET["mdp"];

    $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
    $stmt = $pdo->prepare ("SELECT * FROM salarie WHERE sal_id=:user AND sal_mdp=PASSWORD(:mdp)");
    $stmt->bindParam ("user", $user,PDO::PARAM_STR);
    $stmt->bindParam ("mdp", $mdp,PDO::PARAM_STR);
    $stmt->execute ();

    if ($ligne = $stmt->fetch()){
        
        $output[] = $ligne;
        
    } else {
        $output[] = array("erreur" => "Erreur connexion");
    }

    echo(json_encode($output));

?>