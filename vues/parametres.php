<?php
    session_start();

    if(!isset($_SESSION["numero"])){
        require_once("../vues/error-not-connected.php");
        die();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style_header.css">
    <link rel="stylesheet" href="../style/style-bulle-etat.css">
    <link rel="stylesheet" href="../style/style_parametres.css">
    <title>Epoka - Paramètrage</title>
</head>
<body>

    <?php
        /* HEADER */
        require_once("../vues/header.php");

        if($_SESSION ["peutPayer"] != 1){
        ?>

        <div id="bulle-etat-non-autorise">
            <p>Vous n'êtes pas autorisé à accéder à cette page !</p>
        </div>

        <?php
        } else {

            $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
            $stmt = $pdo->prepare ("SELECT * FROM param");
            $stmt->execute ();

            $valeurs = $stmt -> fetch();

        ?>
            <!-- CONTENUE DE LA PAGE -->

            <h1>Paramètrage de l'application</h1>


            <!-- Montant au KM -->
            <h2>Montant du remboursement au km</h2>

            <form action="../script/updateParamRemboursement.php" method="GET" id="form_remboursement">
                <label for="remboursement">Remboursement au Km :</label>
                <input type="text" id="remboursement" name="remboursement" value="<?php echo($valeurs['prixKm']) ?>"required>

                <br /><br />

                <label for="indemnite">Indemnité d'hébergement :</label>
                <input type="text" id="indemnite" name="indemnite" value="<?php echo($valeurs['prixJournee']) ?>" required>

                <br /><br />

                <input type="submit" value="Valider">

            </form>
           
            <br /><br />
            
            <!-- Distance entre villes -->

            <?php
                $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
                $stmt = $pdo->prepare (" SELECT * FROM ville WHERE vil_categorie < 3 ORDER BY vil_categorie, vil_nom");
                $stmt->execute ();
    
                $villes = $stmt -> fetchAll();
            ?>

            <h2>Distance entre villes</h2>
            <form action="../script/ajoutDistance.php" method="GET" id="distanceEntreVille">
                <label for="ville1">De :</label>
                <select id="ville1" name="ville1">
                    <?php foreach($villes as $ville){ ?>
                        <option value="<?php echo($ville['vil_id']) ?>"><?php echo($ville['vil_nom']) ?></option>
                    <?php } ?>
                </select>

                <label for="ville2">à :</label>
                <select id="ville2" name="ville2">
                    <?php foreach($villes as $ville){ ?>
                        <option value="<?php echo($ville['vil_id']) ?>"><?php echo($ville['vil_nom']) ?></option>
                    <?php } ?>
                </select>

                <label for="distance">Distance en km :</label>
                <input type="text" name="distance">

                <br /><br />

                <input type="submit" value="Valider">
            </form>

            <br /><br />            

            <!-- Distances déjà saisie -->
            <h2>Distances entre villes déjà saisies</h2>

            <?php
                $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
                $stmt = $pdo->prepare ("SELECT * FROM distance");
                $stmt->execute ();
    
                $distances = $stmt -> fetchAll();
            ?>

            <table>
                <tr>
                    <th>De</th>
                    <th>A</th>
                    <th>Km</th>
                </tr>
                
                 <?php foreach($distances as $distance){ ?>
                <tr>
                   
                </tr>
                <?php } ?>
            </table>

        <?php 
        }
        ?>


</body>
</html>