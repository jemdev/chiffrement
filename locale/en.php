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
 *
 * Note : (Translation may be improved)
 *
 * @version  English
 */
$msgs_exceptions = array(
    'extension_mcrypt_absente'      => "The mcrypt extension is required in PHP configuration to use %s.",
    'extension_openssl_absente'     => "openssl extension is required in PHP configuration to use %s.",
    'extension_mcrypt_absente_phpv' => "The mcrypt extension is required in PHP configuration to use %s. Warning, your PHP version could not be determined, the mcrypt class is disabled after PHP version 7.1",
    'algo_inconnu'                  => "Algorithm « %s » unknown, it was not listed in the list available for OpenSSL. Available algorithms : %s",
    'extension_mcrypt_obsolete'     => "The mcrypt extension is no longer active in your PHP version (%s). Consider updating this library to version 2",
    'longueur_vecteur_init'         => "The length of the itinialization vector must be equal to %s",
);
