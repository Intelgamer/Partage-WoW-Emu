<?php
#######################################################################################################################
## 
## Fichier de configuration du script. (Non modifié)
##
## == CONFIGURATION GENERAL DU SCRIPT ==
$config_global = array(
    "hoteMySQL"         => "localhost",     // Adresse où se trouve le serveur MySQL.
    "nomDeCompteMySQL"  => "root",          // Nom de compte du serveur MySQL.
    "motDePasseMySQL"   => "",              // Mot de passe du serveur MySQL.
    
    "bddCompte"         => "auth",          // Base de données des comptes.
);
##
## == CONFIGURATION DES OPTIONS DU SCRIPT ==
$config_options = array(
    "modeDebug"         => false,           // Cette option sert à activer le mode débug. Celui-ci fera apparaître toutes les erreurs. (Utile lors de la configuration)
                                            // true = activé, false = désactivé.
    
    "captcha"           => true,            // Cette option sert à activer le captcha. Celui-ci lutte contre les robots. (conseillé)
                                            // true = activé, false = désactivé.
    
    "cgu"               => false,           // Cette option sert à activer la case des conditions générales d'utilisation. (facultatif)
                                            // true = activé, false = désactivé.
    "lienCgu"           => "#",             // Le lien de votre page CGU.
);
##
#######################################################################################################################