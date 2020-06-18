<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Purple Whale Connexion </title>
            <meta charset="UTF-8">

    <link href="https://fonts.googleapis.com/css?family=Fredoka+One&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="../semantic/semantic.min.css">
    </head>

    <body  style="background-color: #FFEEEE;">

        <div class="ui menu" style="background-color: #BC8F8F;"><!-- Top fixed ? -->
            <div class="ui medium header item">
            <php echo $_SESSION['username']; ?>
            </div>
            <a href="/profile/" class="item"> <!-- A complete -->
                <i class="icon home"> </i> Accueil
            </a>
            <a href="/decouverte" class="item"> <!-- A complete -->
                <i class="icon space shuttle"></i> Découverte
            </a>
            <div class="right menu">
                <div class="item">
                    <form class="ui form" action="/search" method="Post">
                        <div class="ui icon input">
                            <input type="text" name="search" placeholder="Recherche...">
                            <i class="search link icon"></i>
                        </div>
                    </form>
                </div>
                <a href="/public/<?php echo $_SESSION['id'] ?>" class="item">
                    <i class="icon user"> </i> Profile
                </a>
        
                <a href="/disconnect" class="item">
                    <i class="icon power off"> </i> Déconnexion
                </a>
            </div>
        </div>

        
        <div class="ui two column centered grid">
            <div class="column">
                <div class="ui bulleted list">
                    <?php
                    if (isset($params['search'])) {
                    foreach ($params['search'] as $account) { ?>
                    <div class="item">
                        <a href="/public/<?php echo $account->getId(); ?>"> <?php echo htmlspecialchars($account->getSpeudo()); ?></a>
                    </div>
                    <?php }} ?>
                </div>
            </div>
        </div>
    </body>
</html>