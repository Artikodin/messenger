<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messagerie : <?php echo $_SESSION['pseudo']; ?></title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="messagerie-header">
        <span><?php echo $_SESSION['pseudo']; ?> parle avec <?php echo $_SESSION['recepteur']; ?></span>
        <div id="exit-messagerie"></div>
    </div>
    <div class="messagerie-body">
        <div id="messagerie-left">
            <!--File des membres-->
        </div>
        <div class="messagerie-right">
            <div class="messagerie-top">

                <div id="messagerie-conversation">
                    <!--File des messages-->
                </div>
                
            </div>
            <div class="messagerie-bottom">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="text-container">
                    <input type="text" name="contenu" id="contenu" placeholder="Tapez vore message...">
                </div><!--
                --><div class="submit-container">
                    <button type="submit" name="send" id="send">Envoyer</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script src="script/js/jQuery3.1.js"></script>
    <script src="script/js/script.js"></script>
</body>
</html>