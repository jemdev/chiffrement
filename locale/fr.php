<?php
namespace jemdev\chiffrement;
/**
 * @package     jemdev\chiffrement
 *
 * Ce code est fourni tel quel sans garantie.
 * Vous avez la liberté de l'utiliser et d'y apporter les modifications
 * que vous souhaitez. Vous devrez néanmoins respecter les termes
 * de la licence CeCILL dont le fichier est joint à cette librairie.
 * {@see http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html}
 */
/**
 * Messages d'erreur / Error messages
 * @version  Français
 */
$msgs_exceptions = array(
    'extension_mcrypt_absente'      => "L'extension mcrypt est requise dans la configuration de PHP pour utiliser %s.",
    'extension_openssl_absente'     => "L'extension openssl est requise dans la configuration de PHP pour utiliser %s.",
    'extension_mcrypt_absente_phpv' => "L'extension mcrypt est requise dans la configuration de PHP pour utiliser %s. Attention, votre version de PHP n'a pu être déterminée, la classe mcrypt est désactivée après la version 7.1 de PHP",
    'algo_inconnu'                  => "Algorithme « %s » inconnu, il n'a pas été répertorié dans la liste disponible pour OpenSSL. Algorithmes disponibles : %s",
    'extension_mcrypt_obsolete'     => "L'extension mcrypt n'est plus active dans votre version de PHP (%s). Considérez la mise à jour de cette librairie en version 2",
    'longueur_vecteur_init'         => "La taille du vecteur d'itinialisation doit être égale a %s",
);
