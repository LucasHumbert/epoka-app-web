<?php
    session_start();

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
    <title>Epoka - Paramètrage</title>
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

            //requête qui récupère les paramètres actuels de la base qu'on affichera dans les inputs
            $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
            $stmt = $pdo->prepare ("SELECT * FROM param");
            $stmt->execute ();

            $valeurs = $stmt -> fetch();

            //bulles qui s'affichent si on à ajouté une info ou si il y a une erreur
            if(isset($_SESSION["ajout"])){
                echo("<div id='bulle-etat-connecte'><p>". $_SESSION["ajout"] ."</p></div>");
                unset($_SESSION["ajout"]);
            }

            if(isset($_SESSION["error"])){
                echo("<div id='bulle-etat-error'><p>". $_SESSION["error"] ."</p></div>");
                unset($_SESSION["error"]);
            }

        ?>
            <!-- CONTENU DE LA PAGE -->
            <section id="sect_param">
                <h1>Paramètrage de l'application</h1>


                <!-- Montant au KM -->
                <h2>Montant du remboursement au km</h2>

                <form action="../script/updateParamRemboursement.php" method="GET" id="form_remboursement">
                    <div id="div_param">
                        <div class="form-input">                            
                            <input type="text" id="remboursement" name="remboursement" autocomplete="off" value="<?php echo($valeurs['prixKm']) ?>"required>
                            <label for="remboursement">Remboursement au Km :</label>
                        </div>

                        <br />

                        <div class="form-input">
                            <input type="text" id="indemnite" name="indemnite" autocomplete="off" value="<?php echo($valeurs['prixJournee']) ?>" required>
                            <label for="indemnite">Indemnité d'hébergement :</label>
                        </div>
                    </div>

                    <br />
                    <button>
                        <p>Valider</p>    

                        <svg width="30" height="25" viewBox="0 0 30 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0)">
                                <path id="dark1" d="M15.4007 12.1536C15.6674 12.3075 15.6674 12.6925 15.4007 12.8464L6.85019 17.7828C6.58352 17.9367 6.2502 17.7443 6.2502 17.4364L6.2502 7.56364C6.2502 7.25572 6.58352 7.06327 6.85019 7.21722L15.4007 12.1536Z" fill="#FFFFFF"/>
                                <path id="light1" d="M10.4007 12.1536C10.6674 12.3075 10.6674 12.6925 10.4007 12.8464L1.85019 17.7828C1.58352 17.9367 1.2502 17.7443 1.2502 17.4364L1.2502 7.56364C1.2502 7.25572 1.58352 7.06327 1.85019 7.21722L10.4007 12.1536Z" fill="#FFFFFF"/>
                                <path id="dark2" d="M10.4007 12.1536C10.6674 12.3075 10.6674 12.6925 10.4007 12.8464L1.85019 17.7828C1.58352 17.9367 1.2502 17.7443 1.2502 17.4364L1.2502 7.56364C1.2502 7.25572 1.58352 7.06327 1.85019 7.21722L10.4007 12.1536Z" fill="#FFFFFF"/>
                            </g>
                            <defs>
                                <clipPath id="clip0">
                                    <rect width="30" height="25" fill="white"/>
                                </clipPath>
                            </defs>
                        </svg>
                    </button>
                    
                </form>
                
                <!-- Distance entre villes -->

                <?php
                    //requête qui récupère les villes de catégorie 1 et 2, qui les tries par catégorie puis par ordre alphabétique
                    //on les affiches dans les select plus bas
                    $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
                    $stmt = $pdo->prepare ("SELECT * FROM ville WHERE vil_categorie < 3 ORDER BY vil_categorie, vil_nom");
                    $stmt->execute ();
        
                    $villes = $stmt -> fetchAll();
                ?>

                <h2>Distance entre villes</h2>
                <form action="../script/ajoutDistance.php" method="GET" id="distanceEntreVille">
                    <div id="select">

                        <div class="formfield-select">
                            <label for="ville1">De :</label>
                            <div class="formfield-select--container">
                                <select id="ville1" name="ville1" required>
                                    <?php foreach($villes as $ville){ ?>
                                        <option value="<?php echo($ville['vil_id']) ?>"><?php echo($ville['vil_nom']) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="formfield-select">
                            <label for="ville2">à :</label>
                            <div class="formfield-select--container">
                                <select id="ville2" name="ville2" required>
                                    <?php foreach($villes as $ville){ ?>
                                        <option value="<?php echo($ville['vil_id']) ?>"><?php echo($ville['vil_nom']) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-input">
                        <input type="text" id="distance" name="distance" autocomplete="off" required>
                        <label for="distance">Distance en km :</label>
                    </div>  

                    <br />

                    <button>
                        <p>Valider</p>    

                        <svg width="30" height="25" viewBox="0 0 30 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0)">
                                <path id="dark1" d="M15.4007 12.1536C15.6674 12.3075 15.6674 12.6925 15.4007 12.8464L6.85019 17.7828C6.58352 17.9367 6.2502 17.7443 6.2502 17.4364L6.2502 7.56364C6.2502 7.25572 6.58352 7.06327 6.85019 7.21722L15.4007 12.1536Z" fill="#FFFFFF"/>
                                <path id="light1" d="M10.4007 12.1536C10.6674 12.3075 10.6674 12.6925 10.4007 12.8464L1.85019 17.7828C1.58352 17.9367 1.2502 17.7443 1.2502 17.4364L1.2502 7.56364C1.2502 7.25572 1.58352 7.06327 1.85019 7.21722L10.4007 12.1536Z" fill="#FFFFFF"/>
                                <path id="dark2" d="M10.4007 12.1536C10.6674 12.3075 10.6674 12.6925 10.4007 12.8464L1.85019 17.7828C1.58352 17.9367 1.2502 17.7443 1.2502 17.4364L1.2502 7.56364C1.2502 7.25572 1.58352 7.06327 1.85019 7.21722L10.4007 12.1536Z" fill="#FFFFFF"/>
                            </g>
                            <defs>
                                <clipPath id="clip0">
                                    <rect width="30" height="25" fill="white"/>
                                </clipPath>
                            </defs>
                        </svg>
                    </button>
                </form>         

                <!-- Affichage des distances déjà saisie -->
                <h2>Distances entre villes déjà saisies</h2>

                <?php
                    //requête qui récupère toutes les distances existantes dans la base
                    $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
                    $stmt = $pdo->prepare ("SELECT dis_km, a.vil_nom as ville1, b.vil_nom as ville2 FROM distance d JOIN ville a ON d.dis_idVilleDepart =a.vil_id JOIN ville b ON d.dis_idVilleArrivee = b.vil_id ORDER BY ville1");
                    $stmt->execute ();
                    $villes = $stmt -> fetchAll();
                ?>

                <div id="ths">
                    <p>De</p>
                    <p>A</p>
                    <p>Km</p>
                </div>

                <div id="tableau">
                    <table>
                        <?php foreach($villes as $ville){ ?>
                        
                        <tr>
                            <td><?php echo($ville['ville1']) ?></td>
                            <td><?php echo($ville['ville2']) ?></td>
                            <td><?php echo($ville['dis_km']) ?></td>
                        </tr>

                        <?php } ?>

                    </table>
                </div>
            </section>
            <br /><br />

        <?php 
        }
        ?>


</body>
</html>