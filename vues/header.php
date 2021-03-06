<header>
    <div id="title">
        Missions Epoka
    </div>

    <?php
        if(isset($_SESSION["numero"])){
    ?>

    <!-- LIENS CONNECTÉ -->
    <div id="links">
        <a href="../script/deconnexion.php">
            <p>Deconnexion</p>
        </a>

        <a href="../vues/validation.php">
            <p>Validation des missions</p>
        </a>

        <a href="../vues/paiement.php">
            <p>Paiement des frais</p>
        </a>

        <a href="../vues/parametres.php">
            <p>Paramétrage</p>
        </a>
    </div>

    <?php
        } else {
    ?>

    <!-- LIENS DÉCONNECTÉ -->
    <div id="links">
        <div class="link">
            <p>Validation des missions</p>
        </div>

        <div class="link">
            <p>Paiement des frais</p>
        </div>

        <div class="link">
            <p>Paramétrage</p>
        </div>
    </div>

    <?php 
        }
    ?>

</header>