<!DOCTYPE HTML>
<html>
 <head>
        <title> Website </title>
        <!--<meta charset="UTF-8">-->
        <link href="./js/index.js">
        <link rel="stylesheet" type="text/css" href="./semantic/semantic.min.css">
  </head>
  <body style="background-color: #BC8F8F;">
    <h1>Welcome to THE Website</h1>
    <div class="log">
    <h2>Log in</h2>
      <form action="/log" method="POST" class="ui form">
            <div class="two fields">
               <div class="Pseudo">
                  <label class="label">Pseudo</label>
                  <input type="text" name="user" placeholder="Pseudo">
               </div>

               <div class="mdp">
                 <label class="label">Mot de Passe</label>
                 <input type="password" name="pass" placeholder="Password">
                </div>
             </div>
             <button type="submit" class="ui circular large purple button"> Connexion</button>
       </form>
    </div>

    <div class="check">
    <h2>Check in</h2>
      <form action="/iscreate" method="POST" class="ui form">
      <div class="field">
          <label class="label">Prénom, Nom</label>
          <div class="two fields">
              <div class="field">
                <input class="input" type="text" value="<?php if (isset ($params['infoUser']['FirstName'])) echo $params['infoUser']['FirstName'] ?>" name="FirstName" placeholder="FirstName">
              </div>

              <div class="field">
                <input type="text" name="LastName" value="<?php if (isset ($params['infoUser']['LastName'])) echo $params['infoUser']['LastName'] ?>" placeholder="LastName">
              </div>
          </div>
   
      <div class="two fields">
          <div class="field">
              <label class="label">Pseudo</label>
              <input type="text" name="newpseudo" value="<?php if (isset ($params['infoUser']['Pseudo'])) echo $params['infoUser']['Pseudo'] ?>" placeholder="Pseudo">
          </div>

          <div class="field">
              <label class="label">Mot de Passe</label>
              <input type="password" name="newMDP" placeholder="Password">
          </div>
      </div>

       <div>
        <label class="label">Mail</label>
        <input type="text" name="mail" value="<?php if (isset ($params['infoUser']['Mail'])) echo $params['infoUser']['Mail'] ?>" placeholder="Mail">
       </div>
    </div>

      <div class="inline field">
          <div class="ui checkbox">
            <input type="checkbox" name="terms">
            <label>Accepte les termes d'utilisations</label>
          </div>
            <a href="/" class="ui circular  large inverted button">Retour</a>
            <button type="submit" class="ui circular  large purple button">Créer</button>
           </div>
      </form>
     </div>

  </body> 


</html>