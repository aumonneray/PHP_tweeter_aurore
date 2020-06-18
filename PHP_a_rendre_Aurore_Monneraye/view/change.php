<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>Modification de message</title>
        <link rel="stylesheet" href="../semantic/semantic.min.css">
    </head>

    <body style="background-color: #FFEEEE;">

        <div class="ui menu" style="background-color: #BC8F8F;"><!-- Top fixed ? -->
            <div class="ui medium header item"><php echo $_SESSION['username']; ?></div>
            <a href="/profile/" class="item"> <!-- A complete -->
                <i class="icon home"> </i> Accueil
            </a>
            <a href="/decouverte" class="item"> <!-- A complete -->
                <i class="icon space shuttle"></i> Découverte
            </a>
            <div class="right menu">
                <div class="item">
                    <form class="ui form" action="/search">
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
                <div class=" ui center aligned segment" style="background-color: #FFF5F5;">
                    Message sans changement: <br>
                    <div class=" ui segment">
                        <?php if (strlen(substr($params['message']->getMessage(), 0,strrpos($params['message']->getMessage(), ' (modifier)'))) === 0) echo htmlspecialchars($params['message']->getMessage()); else echo htmlspecialchars(substr($params['message']->getMessage(), 0,strrpos($params['message']->getMessage(), ' (modifier)'))) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="ui two column centered grid">
            <div class="column">

                <div class=" ui center aligned segment" style="background-color: #FFF5F5;">
            <form action="/message/changeDB" method="POST" class="ui form">
                <input type="hidden" name="id" value= <?php if (isset($params['message'])) echo htmlspecialchars($params['message']->getId()); ?> >
                <input type="text" name="messageChange" value="<?php if (strlen(substr($params['message']->getMessage(), 0,strrpos($params['message']->getMessage(), ' (modifier)'))) === 0) echo htmlspecialchars($params['message']->getMessage()); else echo htmlspecialchars(substr($params['message']->getMessage(), 0,strrpos($params['message']->getMessage(), ' (modifier)'))) ?>" maxlength="140">
                <button type="submit" class="ui purple button">Envoyer</button>
            </form>
        </div>
              </div>
        </div>
    </body>
</html>