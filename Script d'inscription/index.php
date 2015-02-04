<?php
// On inclu les fichiers config et notre autoload.
require_once "lib/config.php";
require_once "lib/autoload.php";

// On appelle session_start() APRÈS avoir enregistré l'autoload.
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Script d'inscription - WoW-Emu</title>
        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body>
        <header>
            <div id="logo"><!-- --></div>
            <nav><!-- --></nav>
        </header>
        <section>
            <?php
            // On se connecte au serveur MySQL pour savoir si le service est disponnible ou pas.
            $db = DBFactory::getMysqlConnexionWithPDO($config_global["hoteMySQL"], $config_global["bddCompte"]);
            
            // Si nous avons appuyé sur le bouton "Inscription".
            if (isset($_POST["inscription"]))
            {
                // Le formulaire étant modifiable par navigateur, nous faisons une nouvelle vérification pour éviter tout abus.
                if ($db === DBFactory::CONNEXION_FAIL) {}
                else {}
            }
            ?>
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" autocomplete="off" >
                <?php // On verifie si le serveur MySQL est bien accéssible. ?>
                <fieldset style="border: none; margin: 0; padding: 0;" <?php if ($db === DBFactory::CONNEXION_FAIL){ echo "disabled=\"disabled\""; } ?>>
                    <input type="text" name="pseudo" value="" placeholder="Nom de compte" /><br />
                    <input type="password" name="motDePasse" value="" placeholder="**********" /><br />
                    <input type="password" name="motDePasse2" value="" placeholder="**********" /><br />
                    <input type="text" name="email" value="" placeholder="Adresse e-mail" /><br />
                    <?php
                    // Si le captcha est activé.
                    if ($config_options["captcha"] === true)
                    {
                        ?>
                        <img src="lib/captcha.php" />
                        <input type="text" name="captcha" value="" placeholder="Captcha" /><br />
                        <?php
                    }
                    
                    // Si les CGU sont activé.
                    if ($config_options["cgu"] === true)
                    {
                        ?>
                        <input type="checkbox" name="cgu" /> <a href="<?php echo $config_options["lienCgu"]; ?>" target="_blank">Conditions Générales d'Utilisation lu</a>.<br />
                        <?php
                    }
                    ?>
                    <input type="submit" value="Inscription" name="inscription" />
                </fieldset>
            </form>
        </section>
        <footer><!-- --></footer>
    </body>
</html>