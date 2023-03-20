<?php
// Inclusion du contrôleur
require_once __DIR__ . '\..\..\..\..\controller\controller.php';
?>
<!-- Contenu de la page -->
<section class="main" id="main">
    <div class="title outlined">
        <h1>Tests relatifs aux posts</h1>
        <hr>
    </div>
    <div class="content">
        <?php
        // Créer un post
        // Récupérer un post
        // Récupérer tous les posts
        ?>
    </div>
</section>
<!-- 
<script src="/common/js/lib/axios.js"></script>
<script>
var reponseObtenue;
axios
    .get("/view/pages/utils/test.php?fetch")
    .then((response) => {
        // Si la réponse ne comprend pas un message "impossible"
        reponseObtenue = response.data;
        console.log(response.data);
    })
    .catch((error) => {
        console.error(error);
    });
</script> -->