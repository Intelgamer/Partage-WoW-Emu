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
                else
                {
                    // Vérification de l'existance des variables.
                    if (isset($_POST["pseudo"]) && isset($_POST["motDePasse"]) && isset($_POST["motDePasse2"]) && isset($_POST["email"]))
                    {
                        // Si le captcha est activé.
                        if ($config_options["captcha"] === true)
                        {
                            // Si la variable $_POST["captcha"] existe.
                            if (isset($_POST["captcha"]))
                            {
                                // Si la variable $_SESSION["capNum"] existe.
                                if (isset($_SESSION["capNum"]))
                                {
                                    // Alors on met dans des variables les informations.
                                    $captcha = $_POST["captcha"];
                                    $captchaSession = $_SESSION["capNum"];
                                }
                                // Sinon.
                                else
                                {
                                    // On met dans une variables l'information donnée et on crée une variable null.
                                    $captcha = $_POST["captcha"];
                                    $captchaSession = NULL;
                                }
                                
                            }
                            // Sinon.
                            else
                            {
                                // On crée une variable null.
                                $captcha = NULL;
                            }
                        }
                        // Sinon.
                        else
                        {
                            // On crée des variables contenant "NoCaptcha".
                            $captcha = "NoCaptcha";
                            $captchaSession = "NoCaptcha";
                        }
                        
                        // Si les Conditions Générales d'Utilisation sont activés.
                        if ($config_options["cgu"] === true)
                        {
                            // Si la variable $_POST["cgu"] existe.
                            if (isset($_POST["cgu"]))
                            {
                                // Si la variable $_POST["cgu"] est différente de NULL.
                                if ($_POST["cgu"] != NULL)
                                {
                                    // Alors on met dans une variable l'information.
                                    $cgu = $_POST["cgu"];
                                }
                                // Sinon.
                                else
                                {
                                    // On crée une variable null.
                                    $cgu = NULL;
                                }
                            }
                            // Sinon.
                            else
                            {
                                // On crée une variable null.
                                $cgu = NULL;
                            }
                        }
                        // Sinon.
                        else
                        {
                            $cgu = "NoCgu";
                        }
                        
                        // On met en Array les informations.
                        $compte = new Inscription(
                            [
                            "pseudo" => $_POST["pseudo"],
                            "motDePasse" => $_POST["motDePasse"],
                            "motDePasse2" => $_POST["motDePasse2"],
                            "email" => $_POST["email"],
                            "captcha" => $captcha,
                            "captchaSession" => $captchaSession,
                            "cgu" => $cgu,
                            "ip" => $_SERVER["REMOTE_ADDR"],
                            ]
                        );
                        
                        echo $compte->captchaSession();
                    }
                }
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
                    
                    // Si les CGU sont activés.
                    if ($config_options["cgu"] === true)
                    {
                        ?>
                        <input type="checkbox" name="cgu" /> <a href="<?php echo $config_options["lienCgu"]; ?>" target="_blank">Conditions Générales d'Utilisation lues</a>.<br />
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