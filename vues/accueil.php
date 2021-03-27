<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <title>Epoka - Accueil</title>
</head>
<body>
    
    <!---- HEADER ---->
    <?php require_once("../vues/header.php")   ?>
    

    <!---- BULLE INFO ---->
    <?php
        if(isset($_SESSION["numero"])){
    ?>

    <div id="bulle-etat-connecte">
        <p><?php echo("Bonjour ". $_SESSION["prenom"] ." ".$_SESSION["nom"]." ! Vous êtes bien connecté") ?></p>
    </div>

    <?php
        } else {
    ?>

    <div id="bulle-deconnecte">
        <p></p>
    </div>
    <?php }?>

    <?php if(!isset($_SESSION["numero"])){ ?>

    <!---- IDENTIFICATION ---->
    <section id="sec_identification">

        <form method="POST" action="../script/connexion.php">

            <h1>Identifiez-vous</h1>

            <div class="form-input">
                
                <input type="text" name="numero" autocomplete="off" required>
                <label for="numero">Numéro</label>
            </div>

            <div class="form-input">
                <input type="password" name="mdp" autocomplete="off" required>
                <label for="mdp">Mot de passe</label>
            </div>

            <div class="form-input">
                <button>
                    <p>Se connecter</p>    

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
            </div>

        </form>

    </section>

    <?php } ?>
</body>
</html>