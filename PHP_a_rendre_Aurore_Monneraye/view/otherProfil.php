<!DOCTYPE HTML>
<html>
<head>
    <title> THE Website</title>
    <meta charset="UTF-8">

    <link href="https://fonts.googleapis.com/css?family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../semantic/semantic.min.css">
</head>

<body style="background-color: #FFEEEE;">


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


<div class="ui grid">
    <div class="twelve wide column">

        <div class="ui center aligned raised segment">
            <h3 class="ui header">Messages</h3>
        </div>

        <div class="ui segment" style="background-color: #FFF5F5;">
            <form action="addmessage" method="POST" class="ui form">
                <div class="fields">
                    <input name="message" placeholder="Message 140 caractères" autocomplete="off" maxlength="140">
                    <button type="submit" class="ui right floated tiny purple button">Envoyer</button>
                </div>
            </form>
        </div>

        <div class=" ui center aligned segment"style="background-color: #FFF5F5;">
                <div class="ui two column centered grid">
                    <div class="column">
                    
                    <?php if (isset($params['message']) && $params['message'] !== null) {
                    foreach ($params['message'] as $message) {?>
                    <?php if ($message->getPseudo() === $_SESSION['username']) {?>
                    <a href="/public/<?php echo $message->getIdCreator(); ?>"></a>

                    <div>
                        <a href="/public/<?php echo htmlspecialchars($message->getIdCreator()); ?>"><?php echo $message->getPseudo(); ?></a>
                        <div><?php echo htmlspecialchars($message->getDate()); ?></div>
                    </div>

                    <form action="/message/modifier" method="POST">
                        <div class="ui segment">
                            <?php echo htmlspecialchars($message->getMessage()); ?> <br>
                            <input type="hidden" name="idmessage" value="<?php echo $message->getId(); ?>" >
                            <button type="submit" class="ui tiny circular second button">modifier</button>
                        </div>
                    </form>
                    <?php }
                    else { ?>
                        <a href="/public/<?php echo $message->getIdCreator(); ?>"></a>

                        <div>
                            <a href="/public/<?php echo htmlspecialchars($message->getIdCreator()); ?>"><?php echo $message->getPseudo(); ?></a>
                            <div><?php echo htmlspecialchars($message->getDate()); ?></div>
                        </div>

                        <div class="ui segment">
                            <?php echo htmlspecialchars($message->getMessage()); ?> <br>
                            <a href="/retweet/<?php echo $message->getId(); ?>" class="ui small animated fade second button">
                                <div class="visible content"><?php echo $message->getRetweet()['nombre']; ?></div>
                                <div class="hidden content">
                                    <i class="retweet icon"></i>
                                </div>
                            </a>
                            <a href="/like/<?php echo $message->getId(); ?>" class="ui small animated fade second button">
                                <div class="visible content"><?php echo $message->getLike()['nombre']; ?></div>
                                <div class="hidden content">
                                    <i class="heart pink icon"></i>
                                </div>
                            </a>
                        </div>
                    <?php }}} ?>
                    </div>
                </div>
            </div>
        </div>


    <div class="four wide column">
        <div class="ui message" style="background-color: #FFF5F5;">
            <h4 class="ui header"><strong>Followers</strong></h4>
        </div>

        <div class="ui segment" style="background-color: #FFF5F5;">
            <div class="ui bulleted list">
            <?php if (isset($params['followers'])) {
                foreach($params['followers'] as $follower) {?>
                    <div class="item">
                        <a href="/public/<?php echo $follower->getId(); ?>"> <?php echo $follower->getSpeudo(); ?></a>
                    </div>
                <?php } }?>
            </div>
        </div>
    </div>
</div>
</body>
</html>