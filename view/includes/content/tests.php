<?php
if (!isset($_SESSION['is_mod']) || $_SESSION['is_mod'] == false) {
    // Si l'utilisateur n'est pas admin, on le redirige vers la page d'accueil
    header('Location: /');
}
?>
<style>
    .button {
        color: white;
        background: var(--lighter);
        border: 5px solid var(--lighter);
        border-radius: 0;
        font-size: 20px;
        padding: 30px;
        display: inline;
        margin: 5px auto 5px auto;
        transition: 0.5s;
        font-family: "Agency FB", sans-serif;
    }

    .button:hover {
        background: rgb(23 24 25);
        border: 5px solid var(--lighter2);
        cursor: pointer;
        transition: 1s;
    }

    .button:active {
        background: radial-gradient(rgb(40 40 40), rgb(23 24 25));
        transition: 1s;
    }

    .main .content p {
        font-size: 30px;
    }
</style>
<!-- Contenu de la page -->
<section class="main" id="main">
    <div class="title outlined">
        <h1>Test de debug de valeur via champ textuel:</h1>
        <hr>
    </div>
    <div class="content">
        <form action="/?page=..\tests\debug" method="POST">
            <input type="text" name="champTexte">
            <textarea type="text" name="zoneTexte"></textarea>
            <input type="submit" class="button" value="Bouton test"></submit>
        </form>
        <div class="title outlined">
            <h1>Autres pages de tests:</h1>
            <hr>
        </div>
        <?php
        echo "<p>";
        $files = scandir(__DIR__ . '\..\tests');
        foreach ($files as $file) {
            if ($file != "." && $file != ".." && $file != "index.php") {
                $file = str_replace(".php", "", $file);
                echo "<a href=\"/?page=..\\tests\\$file\">$file</a><br>";
            }
        }
        echo "</p>";
        ?>
    </div>
</section>