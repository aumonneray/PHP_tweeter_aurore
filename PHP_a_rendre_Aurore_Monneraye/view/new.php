<!DOCTYPE HTML>
<html>
    <head>
        <title>Découverte</title>
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


        <div class="ui center aligned raised segment">
            <h3 class="ui header">Découverte</h3>
        </div>

        

        <div class=" ui center aligned segment"style="background-color: #FFF5F5;">
                <div class="ui two column centered grid">
                    <div class="column">
                    
        			<?php if (isset($params['flux'])) { ?>
            			
               			<?php foreach ($params['flux'] as $flux) { ?>
                    	<?php if ($flux->getPseudo() === $_SESSION['username']) {?>
                        	<a href="/public/<?php echo $flux->getIdCreator(); ?>"></a>

                        <div>
                            <a href="/public/<?php echo htmlspecialchars($flux->getIdCreator()); ?>"><?php echo htmlspecialchars($flux->getPseudo()); ?></a>
                            <div><?php echo htmlspecialchars($flux->getDate()); ?></div>

                        </div>

                        <form action="/message/modifier" method="POST">
                            <div class="ui segment">
                                <?php echo htmlspecialchars($flux->getMessage()); ?> <br>
                                <input type="hidden" name="idmessage" value="<?php echo $flux->getId(); ?>" >
                                <button type="submit" class="ui tiny second button">modifier</button>
                            </div>
                        </form>
                    <?php }
                    else { ?>
                        <a href="/public/<?php echo $flux->getIdCreator(); ?>"></a>

                        <div>
                            <a href="/public/<?php echo htmlspecialchars($flux->getIdCreator()); ?>"><?php echo htmlspecialchars($flux->getPseudo()); ?></a>
                            <div><?php echo htmlspecialchars($flux->getDate()); ?></div>
                        </div>

                        <div class="ui segment">
                            <?php echo htmlspecialchars($flux->getMessage()); ?> <br>
                            <a href="/retweet/<?php echo $flux->getId(); ?>" class="ui small animated fade second button">
                                <div class="visible content"><?php echo $flux->getRetweet()['nombre']; ?></div>
                                <div class="hidden content">
                                	<i class="retweet icon"></i>
                                </div>
                            </a>
                            <a href="/like/<?php echo $flux->getId(); ?>" class="ui small animated fade second button">
                            	<div class="visible content"><?php echo $flux->getLike()['nombre']; ?></div>
                            	<div class="hidden content">
                            		<i class="heart icon"></i>
                            	</div>
                            </a>
                        </div>
                <?php }}} ?>
                </div>
            </div>
        </div>       


    </body>
</html>