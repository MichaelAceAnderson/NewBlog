<!DOCTYPE html>
<html xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml" lang="fr">

<head>
    <!-- On précise comment est encodée la page -->
    <meta charset="UTF-8">
    <!-- On paramètre la largeur de l'appareil -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- On précise la description du site pour les moteurs de recherche -->
    <meta name="description" content="NewBlog est un CMS permettant à un utilisateur de créer son propre blog">
    <script src="/controller/js/styleDebug.js"></script>
    <!-- On définit le titre de la page -->
    <?php
    // Par défaut, le nom du blog n'est pas défini
    $blogName = false;

    // Si la connexion à la base de données a pu être établie
    if (Model::getPdo() != null) {
        // On stocke l'état de l'installation du blog dans une variable
        $blogInstalled = BlogController::isInstalled();

        // Si le blog est installé, tenter de récupérer le nom du blog à partir de la méthode du contrôleur
        if ($blogInstalled) {
            $blogName = BlogController::getBlogName();
        } else {
            // Si le blog n'est pas installé, on détruit la session et ses variables
            unset($_SESSION);
            session_destroy();
        }
    }
    // Si le nom du blog est défini, le mettre en titre, sinon mettre "NewBlog"
    echo $blogName ? '<title>' . $blogName . '</title>' : '<title>NewBlog</title>';
    // Mettre une icône de site
    echo '<link rel="icon" href="' . BlogController::getLogoUrl() . '" />';
    ?>
    <!-- On précharge les polices d'écriture -->
    <link rel="preload" href="/view/style/fonts/agencyfb.ttf" as="font" type="font/ttf" crossorigin="anonymous">
    <link rel="preload" href="/view/style/fonts/LCD.ttf" as="font" type="font/ttf" crossorigin="anonymous">
    <!-- On relie la feuille de style avec la page -->
    <link href="/view/style/styleGeneral.css" rel="stylesheet" onload="sheetLoaded('general')"
        onerror="sheetError('general')">
    <link href="/view/style/styleLight.css" rel="stylesheet" media="(prefers-color-scheme: light)"
        onload="sheetLoaded('light')" onerror="sheetError('light')">
    <link href="/view/style/styleDark.css" rel="stylesheet" media="(prefers-color-scheme: dark)"
        onload="sheetLoaded('dark')" onerror="sheetError('dark')">
    <link href="/view/style/styleMobile.css" rel="stylesheet" onload="sheetLoaded('mobile')"
        onerror="sheetError('mobile')">
    <link href="/view/style/stylePrint.css" rel="stylesheet" media="print" onload="sheetLoaded('print')"
        onerror="sheetError('print')">

</head>