<?php
namespace jemdev\chiffrement;
/**
 * @package     jemdev
*
* Ce code est fourni tel quel sans garantie.
* Vous avez la liberté de l'utiliser et d'y apporter les modifications
* que vous souhaitez. Vous devrez néanmoins respecter les termes
* de la licence CeCILL dont le fichier est joint à cette librairie.
* {@see http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html}
*/
/**
 * Gestion des exception du package de chiffrement.
*
* @author      Jean Molliné <jmolline@gmail.com>
* @package     jemdev
* @subpackage  chiffrement
*/
class Exception extends \Exception
{
    /**
     * Constructeur.
     *
     */
    public function __construct($msg, $code = 0)
    {
        parent::__construct($msg, $code);
    }
}