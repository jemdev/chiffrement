<?php
namespace jemdev\chiffrement;
/**
 * @package jemdev\chiffrement
 */
use jemdev\chiffrement\Exception;
/**
 * Class Crypt [PHP7.4]
 *
 * Permet une utilisation plus aisee de la librairie mcrypt.
 *
 * Classe realisée avec l'utilisation de la librairie de chiffrement openssl
 * Réécriture de la classe originale jemdev\chiffrement\crypt qui utilisait
 * mcrypt devenu obsolète et retirée dès PHP 7.2
 *
 * @author      Jean Molliné <jmolline@gmail.com>
 * @version     2.0beta
 * @since       À partir de PHP 7.2.
 * @package     jemdev
 * @subpackage  crypt
 * @copyright   Jean Molliné 2021
 */
class cryptopenssl implements cryptInterface
{
    /**
     * Algorithme utilise
     *
     * @var String
     */
    private $_algo;
    /**
     * Clef utilisee
     *
     * @var String
     */
    private $_grainDeSel;
    /**
     * Vecteur d'initialisation utilise
     *
     * @var String
     */
    private $_vecteur;
    /**
     * Longueur de la clé de chiffrement.
     *
     * @var Int
     */
    private $_lc;

    private $_aAlgosEncryption;
    private $_locale;
    private $_aMsgsExceptions = array();

    /**
     * Crée une instance de la classe.
     *
     * Rien n'interdit d'utiliser un autre algorithme et un autre mode que ceux
     * définis par défaut dans les paramètres du constructeur, mais il faudra
     * cependant utiliser les mêmes valeurs pour pouvoir déchiffrer une chaine
     * que ceux utilisés lors du chiffrement.<br />
     * Ainsi, on pourra par exemple utilement enregistrer en base de données
     * trois éléments :
     * - La clé de chiffrement, ou grain de sel;
     * - Le vecteur d'initialisation;
     * - La chaine chiffrée.
     *
     * Si on le souhaite, on peut envoyer « null » pour vecteur lors du chiffrement
     * et faire appel à la méthode jem\chiffrement\crypt::getVecteur lors du chiffrement.
     * Dans ce cas, le vecteur sera une chaine aléatoire, ce qui augmente encore la
     * sécurité. Ainsi, chaque nouvelle instance utilisant un vecteur différent, il sera
     * alors totalement improbable d'imaginer qu'on puisse récupérer le contenu original
     * par attaque externe.
     *
     * @param   String  $grainDeSel     Clé de chiffrement.
     * @param   String  $iv             Vecteur d'initialisation
     * @param   String  $algo           Algorithme a utiliser pour le chiffrement/dechiffrement (par
     *                                  defaut « aes-256-cbc »)
     * @param   String  $locale         Langue pour les messages d'erreur (par defaut « fr » => en français).
     */
    public function __construct($grainDeSel, $iv = null, $algo = "aes-256-cbc", $locale = 'fr')
    {
        $this->_locale  = $locale;
        $this->_lc      = 16;
        $this->_setMessagesException();
        if(false === extension_loaded('openssl'))
        {
            $msg = sprintf($this->_aMsgsExceptions['extension_openssl_absente'], __CLASS__);
            throw new Exception($msg, E_USER_ERROR);
        }
        $this->_setAlgosChiffrement();

        /**
         * Initialisation des paramètres de configuration de l'instance.
         */
        if(in_array($algo, $this->_aAlgosEncryption))
        {
            $this->setAlgo($algo);
        }
        else
        {
            $msg = sprintf($this->_aMsgsExceptions['algo_inconnu'], $algo);
            throw new Exception($msg, E_USER_ERROR);
        }
        $this->setVecteur($iv);

        /**
         * Initialisation de l'instance elle-même.
         */
        $this->setGrainDeSel($grainDeSel);
    }

    /**
     * Chiffre un texte.
     * @param   string  $chaineclaire
     * @return  string
     */
    public function encrypt($chaineclaire, $encode = false): string
    {
        $chainechiffree = openssl_encrypt($chaineclaire, $this->_algo, $this->_grainDeSel, $option = 0, $this->_vecteur);
        return (false === $encode) ? $chainechiffree : base64_encode($chainechiffree);
    }

    /**
     * Déchiffre une chaîne de caractères
     *
     * @param   string  $chainechiffree
     * @return  string  Chaîne déchiffrée.
     */
    public function decrypt($chainechiffree, $decode = false): string
    {
        $txt = (true === $decode) ? base64_decode($chainechiffree) : $chainechiffree;
        $chaineclaire = openssl_decrypt($txt, $this->_algo, $this->_grainDeSel, $option = 0, $this->_vecteur);
        return $chaineclaire;
    }

    /**
     * Récupère l'algorithme utilisé.
     *
     * Renvoie le nom de l'algorythme de chiffrement/dechiffrement en cours d'utilisation
     * @return String
     */
    public function getAlgo(): string
    {
        return $this->_algo;
    }

    /**
     * Permet de modifier l'algorithme a utiliser
     *
     * @param String $algo
     */
    public function setAlgo($algo = null): cryptopenssl
    {
        if(isset($algo) && in_array($algo, $this->_aAlgosEncryption))
        {
            $this->_algo = $algo;
        }
        else
        {
            $sAlgosDispos = "'". implode("', ", $this->_aAlgosEncryption) ."'";
            $msg = sprintf($this->_aMsgsExceptions['algo_inconnu'], $algo, $sAlgosDispos);
            throw new Exception($msg, E_USER_ERROR);
        }
        return $this;
    }

    /**
     * Creer un vecteur d'initialisation.
     *
     * @param   String  $vecteur    Vecteur à utiliser. Optionnel : s'il n'est pas fourni, un vecteur alétoire sera créé.
     */
    public function setVecteur($vecteur = null): cryptopenssl
    {
        if(is_null($vecteur))
        {
            $this->_vecteur = $this->_createRandomVector();
        }
        else
        {
            $max    = $this->_getVectorSize();
            $v      = substr($vecteur, 0, $max);
            if(strlen($v) != $max)
            {
                $msg = sprintf($this->_aMsgsExceptions['longueur_vecteur_init'], $this->_getVectorSize());
                throw new Exception($msg, E_USER_NOTICE);
            }
            else
            {
                $this->_vecteur = $v;
            }
        }
        return $this;
    }

    /**
     * @return  String  Renvoie la valeur du vecteur d'initilisation
     */
    public function getVecteur(): string
    {
        return $this->_vecteur;
    }

    /**
     * Définir une clé de chiffrement
     * @param   string      $grainDeSel
     * @return  cryptopenssl
     */
    public function setGrainDeSel($grainDeSel = null): cryptopenssl
    {
        if(!is_null($grainDeSel) && !empty($grainDeSel))
        {
            $this->_grainDeSel = $grainDeSel;
        }
        else
        {
            $this->_grainDeSel = openssl_random_pseudo_bytes($this->_lc);
        }
        return $this;
    }

    /**
     * Récupérer la clé de chiffrement en cours d'utilisation.
     * @return string
     */
    public function getGrainDeSel(): string
    {
        return $this->_grainDeSel;
    }

    /**
     * Impression par defaut d'un objet de la classe cryptopenssl
     */
    public function __toString()
    {
        $msg  = "Algorithme de chiffrement/d&eacute;chiffrement utilis&eacute; : ".$this->getAlgo()."<br />";
        $msg .= "Vecteur d'initialisation cr&eacute;&eacute;/utilis&eacute; : ".$this->getVecteur();
        return $msg;
    }

    private function _setMessagesException(): void
    {
        $reploc         = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . "locale";
        $msgs_locale    = $reploc . DIRECTORY_SEPARATOR . $this->_locale .".php";
        if(!file_exists($msgs_locale))
        {
            $msgs_locale = $reploc . DIRECTORY_SEPARATOR ."fr.php";
        }
        include($msgs_locale);
        $this->_aMsgsExceptions = $msgs_exceptions;
    }

    private function _setAlgosChiffrement()
    {
        $this->_aAlgosEncryption = openssl_get_cipher_methods(true);
    }

    /**
     * Crée et retourne un vecteurd'initialisation aléatoire.
     *
     * @return String Renvoie un vecteur créé pseudo-aleatoirement
     */
    private function _createRandomVector(): string
    {
        $vd = $this->_getVectorSize();
        return openssl_random_pseudo_bytes($vs, true);
    }

    /**
     * @return  int     Taille obligatoire a utiliser pour le vecteur d'initialisation
     */
    private function _getVectorSize(): int
    {
        return openssl_cipher_iv_length($this->_algo);
    }

}

