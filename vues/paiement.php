<?php
    session_start();

    //si personne n'est connecté on renvoi sur la page d'accueil
    if(!isset($_SESSION["numero"])){
        header('location: ../vues/accueil.php');
        die();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <title>Epoka - Paiement</title>
</head>
<body>

    <?php
        /* HEADER */
        require_once("../vues/header.php");

        //si la personne connecté n'est pas autorisé à payer alors on la jette
        if($_SESSION ["peutPayer"] != 1){
        ?>

        <div id="bulle-etat-error">
            <p>Vous n'êtes pas autorisé à accéder à cette page !</p>
        </div>

        <?php
        } else {
            $numero = $_SESSION ['numero'];
            setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);  //permet d'afficher la date en français

            //requête qui va nous permettre d'afficher toutes les infos dans un tableau
            $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
            $stmt = $pdo->prepare ("SELECT * FROM salarie, mission, ville WHERE sal_id = mis_idSalarie AND mis_idDestination = vil_id AND mis_validation = 1");
            $stmt->execute ();

            $answers = $stmt -> fetchAll();
            
        ?>
            <!-- CONTENU DE LA PAGE -->
            <section id="sect_tableau">
                <h1>Paiement des missions</h1>
                <table>
                    <tr id="titles">
                        <th>Nom du salarié</th>
                        <th>Prénom du salarié</th>
                        <th>Début de la mission</th>
                        <th>Fin de la mission</th>
                        <th>Lieu de la mission</th>
                        <th>Montant</th>
                        <th>Paiement</th>
                    </tr>

                    <?php 
                        //pour chaque ligne donc chaque mission on appel la fonction qui va calculer le montant et on le stock
                        foreach($answers as $answer){ 
                            $montantMission = calculMontant($answer['mis_id'], $answer['sal_idAgence']);
                    ?>
                    
                        
                    <tr>
                        <td><?php echo($answer['sal_nom']) ?></td>
                        <td><?php echo($answer['sal_prenom']) ?></td>
                        <td><?php echo(strftime("%A %e %B %Y", strtotime($answer['mis_dateDebut']))) ?></td>
                        <td><?php echo(strftime("%A %e %B %Y", strtotime($answer['mis_dateFin']))) ?></td>
                        <td><?php echo($answer['vil_nom']." (".$answer['vil_cp'].")") ?></td>
                        <td><center><?php

                            //on va chercher si un montant est déjà renseigné dans la base
                            $stmt = $pdo->prepare ("SELECT mis_id, mis_montant FROM mission WHERE mis_id = :idMission AND mis_montant IS NOT NULL");
                            $stmt->bindParam ("idMission", $answer['mis_id'],PDO::PARAM_STR);
                            $stmt->execute ();

                            //si oui on l'affiche
                            if ($ligne = $stmt -> fetch()){
                                echo(number_format($ligne['mis_montant'], 2, '.', '')."€");
                            
                            //sinon on affiche le montant calculé plus haut
                            } else {
                                echo($montantMission);
                            }

                        ?></center></td>
                        <td>

                            <?php 
                                //si la mission n'est pas encore payé alors on affiche un bouton qui va appeler le script updateMissionPaiement 
                                //en lui transmettant dans des input hidden l'id et le montant de la mission
                                if($answer['mis_paiement'] == 0){ 
                            ?>

                            <form action="../script/updateMissionPaiement.php" method="GET">

                                <input type="hidden" name="id" value="<?php echo($answer['mis_id']) ?>">

                                <input type="hidden" name="montant" value="<?php echo($montantMission) ?>">

                                <input type="submit" value="Rembourser" <?php if($_SESSION["error-distance"] == "erreur"){ echo("disabled");}; //on désactive le bouton si on trouve une erreur de distance ?>>
                                
                            </form>

                            <?php
                                //sinon on affiche simplement que la mission est remboursé 
                                } else { 
                            ?>

                                <p>Remboursée</p>

                            <?php } ?>

                        </td>
                    </tr>

                    <?php } ?>
                
                </table>
            </section>

        <?php
            }

            //fonction appelé avec 2 paramètres
            //l'id de la mission permet de récupérer les information en lien avec la mission pour laquelle on calcul le montant
            //l'id de l'agence permet de retrouver la ville de cette dernière
            function calculMontant($idMission, $salarieIdAgence){
                
                //récupération de la ville de l'agence
                $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
                $stmt = $pdo->prepare ("SELECT age_ville, vil_nom FROM agence, ville WHERE age_ville = vil_id AND age_id = :idAgence");
                $stmt->bindParam ("idAgence", $salarieIdAgence,PDO::PARAM_INT);
                $stmt->execute ();
                $stmtVille = $stmt -> fetch();

                $ville1 = $stmtVille['age_ville'];
                $nomVille1 = $stmtVille['vil_nom'];

                //récupération de la ville de destination
                $stmt = $pdo->prepare ("SELECT mis_idDestination, vil_nom FROM mission, ville WHERE mis_idDestination = vil_id AND mis_id = :idMission");
                $stmt->bindParam ("idMission", $idMission,PDO::PARAM_INT);
                $stmt->execute ();
                $stmtVille = $stmt -> fetch();

                $ville2 = $stmtVille['mis_idDestination'];
                $nomVille2 = $stmtVille['vil_nom'];

                //ici on renverse les deux villes en fonction du code postal afin d'être sur de pouvoir trouver la distance plus bas
                //car quand on insere les distances on place toujours la ville ayant le plus petit cp en premier
                if ($ville1 > $ville2){
                    $temp = $ville2;
                    $ville2 = $ville1;
                    $ville1 = $temp;

                    $temp = $nomVille2;
                    $nomVille2 = $nomVille1;
                    $nomVille1 = $temp;
                }

                //récupération de la distance entre les deux villes
                $stmt = $pdo->prepare ("SELECT dis_km FROM distance WHERE dis_idVilleDepart = :ville1 AND dis_idVilleArrivee = :ville2");
                $stmt->bindParam ("ville1", $ville1,PDO::PARAM_INT);
                $stmt->bindParam ("ville2", $ville2,PDO::PARAM_INT);
                $stmt->execute ();
                $stmtKm = $stmt -> fetch();

                $distance = $stmtKm['dis_km'];

                $_SESSION["error-distance"] = "";

                //si on ne trouve pas de distance on arrete la fonction en affichant un message dans le tableau
                //la variable de session permettra de désactiver le bouton de paiement tant que la distance n'est pas trouvé
                if (!isset($distance)){
                    $_SESSION["error-distance"] = "erreur";
                    return("Distance entre $nomVille1 et <br />$nomVille2 non renseignée");
                }
             
                

                //nombre de jours de la mission
                //on utilise la fonction sql DATEDIFF et on ajoute 1 car on considère que l'on part le matin du premier jouer et qu'on rentre le soir du dernier
                $stmt = $pdo->prepare ("SELECT DATEDIFF(mis_dateFin, mis_dateDebut) + 1 as dateDiff FROM mission WHERE mis_id = :idMission");
                $stmt->bindParam ("idMission", $idMission,PDO::PARAM_INT);
                $stmt->execute ();
                $stmtJour = $stmt -> fetch();

                $nbJours = $stmtJour['dateDiff'];



                //récupération des paramètres
                $stmt = $pdo->prepare ("SELECT * FROM param");
                $stmt->execute ();
                $stmtParam = $stmt -> fetch();

                $prixKm = $stmtParam['prixKm'];
                $prixJournee = $stmtParam['prixJournee'];



                //calcul
                //on multiplie par deux car il faut compter l'aller et le retour
                $montant = ($distance * $prixKm) * 2 + ($nbJours * $prixJournee);

                //retourne le montant trouvé
                return(number_format($montant, 2, '.', '')."€");
            }

        ?>

</body>
</html>