<?php
    session_start();    
    
    //vérification du numéro d'utilisateur (erreur si nul ou pas un nombre)
    if (empty($_POST["numero"])){
        $_SESSION["error"] = "Numéro absent";
        die(header('location: ../index.php'));
    }

    if (!is_numeric($_POST["numero"])){
        $_SESSION["error"] = "Numéro ou mot de passe incorrect";
        die(header('location: ../index.php'));
    }
    $numero = $_POST["numero"];

    //vérification du mot de passe (erreur si nul)
    if (empty($_POST["mdp"])){
        $_SESSION["error"] = "Mdp absent";
        die(header('location: ../index.php'));
    }
    $mdp = $_POST["mdp"];

    $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
    $stmt = $pdo->prepare ("SELECT * FROM salarie WHERE sal_id=:user AND sal_mdp=PASSWORD(:mdp)");
    $stmt->bindParam ("user", $numero,PDO::PARAM_STR);
    $stmt->bindParam ("mdp", $mdp,PDO::PARAM_STR);
    $stmt->execute ();

    if ($ligne = $stmt->fetch()){
        
        //variables de profil
        $_SESSION ["numero"] = $numero;
        $_SESSION ["nom"] = $ligne["sal_nom"];
        $_SESSION ["prenom"] = $ligne["sal_prenom"];

        //variables d'autorisations qui permettent de vérifier si l'utilisateur connecté peut accéder à certaines pages
        $_SESSION ["peutValider"] = $ligne["sal_peutValider"];
        $_SESSION ["peutPayer"] = $ligne["sal_peutPayer"];

        header('location: ../index.php');
        
    } else {
        $_SESSION["error"] = "Numéro ou mot de passe incorrect";
        header('location: ../index.php');
    }

?>