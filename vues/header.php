<header>
    <div id="title">
        Missions Epoka
    </div>

    <?php
        if(isset($_SESSION["numero"])){
    ?>

    <!-- LIENS CONNECTÉ -->
    <div id="links">
        <a href="script/deconnexion.php">
            <p>Deconnexion</p>
        </a>

        <a>
            <p>Validation des missions</p>
        </a>

        <a>
            <p>Paiement des frais</p>
        </a>

        <a>
            <p>Paramétrage</p>
        </a>
    </div>

    <?php
        } else {
    ?>

    <!-- LIENS DÉCONNECTÉ -->
    <div id="links">
        <a href="index.php">
            <p>Connexion</p>
        </a>

        <a>
            <p>Validation des missions</p>
        </a>

        <a>
            <p>Paiement des frais</p>
        </a>

        <a>
            <p>Paramétrage</p>
        </a>
    </div>

    <?php 
        }
    ?>

</header>

<?php
    if(isset($_SESSION["numero"])){
?>

<div id="bulle-etat-connexion">
    <p><?php echo("Bonjour ". $_SESSION["prenom"] ." ".$_SESSION["nom"]." ! Vous êtes bien connecté") ?></p>
</div>

<?php
    } else {
?>

<div id="bulle-deconnecte">
    <p></p>
</div>
<?php }?>