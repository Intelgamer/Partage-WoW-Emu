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
                        
                        // On met en Array les informations tout en instanciant la classe Inscription.
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
                        
                        // Gestion des erreurs lié à l'envoie du formulaire.
                        foreach ($compte->erreurs() as $erreurs => $value)
                        {
                            switch ($value)
                            {
                                case Inscription::PSEUDO_INVALIDE:
                                    $message[] = "<p class=\"erreur\">Le champ Pseudo est invalide et/ou vide.</p>";
                                    break;
                                case Inscription::MOT_DE_PASSE_INVALIDE:
                                    $message[] = "<p class=\"erreur\">Le champ Mot de passe est invalide et/ou vide.</p>";
                                    break;
                                case Inscription::MOT_DE_PASSE_2_INVALIDE:
                                    $message[] = "<p class=\"erreur\">Le champ Mot de passe repeat est invalide et/ou vide.</p>";
                                    break;
                                case Inscription::EMAIL_INVALIDE:
                                    $message[] = "<p class=\"erreur\">Le champ Email est invalide et/ou vide.</p>";
                                    break;
                                case Inscription::CAPTCHA_INVALIDE:
                                    $message[] = "<p class=\"erreur\">Le champ Captcha est invalide et/ou vide.</p>";
                                    break;
                                case Inscription::CAPTCHA_SESSION_INVALIDE:
                                    $message[] = "<p class=\"erreur\">Une erreur est survenue lors de l'envoie du formulaire. Veuillez contacter l'administrateur du site avec ce code erreur: <strong>0001</strong></p>";
                                    break;
                                case Inscription::CGU_INVALIDE:
                                    $message[] = "<p class=\"erreur\">Le champ CGU n'est pas coché. Veuillez lire les Conditions Générales d'Utilisation et les accepters.</p>";
                                    break;
                                case Inscription::IP_INVALIDE:
                                    $message[] = "<p class=\"erreur\">Une erreur est survenue lors de l'envoie du formulaire. Veuillez contacter l'administrateur du site avec ce code erreur: <strong>0002</strong></p>";
                                    break;
                            }
                        }
                        
                        // Si les informations sont valide. (Non vide)
                        if ($compte->isValide())
                        {
                            // Si les deux mots de passe sont identiques.
                            if ($compte->motDePasseValide())
                            {
                                // Si l'adresse email est valide. (@ et .xx)
                                if ($compte->emailValide())
                                {
                                    // Si le captcha est valide.
                                    if ($compte->captchaValide())
                                    {
                                        
                                    }
                                    // Sinon.
                                    else
                                    {
                                        // On ecrit un message d'erreur et un autre d'information.
                                        $message[] = "<p class=\"erreur\">Le captcha est invalide.</p>";
                                        $message[] = "<p class=\"info\">Note: Le captcha est sensible à la casse</p>";
                                    }
                                }
                                // Sinon.
                                else
                                {
                                    // On ecrit un message d'erreur.
                                    $message[] = "<p class=\"erreur\">L'adresse email est invalide.</p>";
                                }
                            }
                            // Sinon.
                            else
                            {
                                // On ecrit un message d'erreur.
                                $message[] = "<p class=\"erreur\">Les  Mots de passe sont différents.</p>";
                            }
                        }
                        else { /* On ne met rien ici puisque les erreurs sont déjà communiqué */ }
                    }
                }
            }
            
            // Si la variable $message existe.
            if (isset($message))
            {
                // Boucle d'affichage des messages.
                foreach ($message as $message)
                {
                    echo $message;
                }
            }
            ?>
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" autocomplete="off" >
                <?php // On verifie si le serveur MySQL est bien accéssible. ?>
                <fieldset style="border: none; margin: 0; padding: 0;" <?php if ($db === DBFactory::CONNEXION_FAIL){ echo "disabled=\"disabled\""; } ?>>
                    <input type="text" name="pseudo" value="<?php if (isset($compte)) { echo $compte->pseudo(); } ?>" placeholder="Nom de compte" /><br />
                    <input type="password" name="motDePasse" value="<?php if (isset($compte)) { echo $compte->motDePasse(); } ?>" placeholder="**********" /><br />
                    <input type="password" name="motDePasse2" value="<?php if (isset($compte)) { echo $compte->motDePasse2(); } ?>" placeholder="**********" /><br />
                    <input type="text" name="email" value="<?php if (isset($compte)) { echo $compte->email(); } ?>" placeholder="Adresse e-mail" /><br />
                    <?php
                    // Si le captcha est activé.
                    if ($config_options["captcha"] === true)
                    {
                        ?>
                        <img src="lib/captcha.php" />
                        <input type="text" name="captcha" placeholder="Captcha" /><br />
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