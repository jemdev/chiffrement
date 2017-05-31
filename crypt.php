<?php
namespace jemdev\chiffrement;
/**
 * @package jemdev
 */
/**
 * Class Crypt [PHP5]
 *
 * Permet une utilisation plus aisee de la librairie mcrypt.
 *
 * Classe realisée suite a un très bon didactitiel d'introduction au
 * chiffrage avec la librairie de chiffrement mcrypt posté par Ripat
 * sur Lumadis.be : {@link http://www.lumadis.be/dvp/crypt/tuto_crypt.php}
 *
 * @author      Jean Molliné <jmolline@gmail.com>
 * @version     1.0
 * @package     jemdev
 * @subpackage  crypt
 * @copyright   Jean Molliné 2006
 */
class crypt
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
     * Mode de chiffrement utilise
     *
     * @var String
     */
    private $_mode;
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
    private $_ks;

    private $_aModesEncryption;
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
     * et faire appel à la méthode jem_chiffrement_crypt::getVecteur lors du chiffrement.
     * Dans ce cas, le vecteur sera une chaine aléatoire, ce qui augmente encore la
     * sécurité. Ainsi, chaque nouvelle instance utilisant un vecteur différent, il sera
     * alors totalement improbable d'imaginer qu'on puisse récupérer le contenu original
     * par attaque externe.
     *
     * @param   String  $grainDeSel     Clé de chiffrement.
     * @param   String  $algo           Algorithme a utiliser pour le chiffrement/dechiffrement (par
     *                                  defaut rijndael-256)
     * @param   String  $mode           Mode de chiffrement a utiliser (par defaut nofb)
     * @param   String  $vecteur        Vecteur d'initialisation. La valeur « rand » (par défaut)
     *                                  permet de creer un vecteur pseudo-aleatoire
     * @param   String  $locale         Langue pour les messages d'erreur.
     * @param   String  $iv             Vecteur d'initialisation (facultatif, ne sert que pour déchiffrer)
    */
    public function __construct($grainDeSel, $algo = "rijndael-256", $mode = "nofb", $vecteur = "rand", $locale = 'fr', $iv = null)
    {
        $this->_locale  = $locale;
        $this->_setMessagesException();
        if(false === extension_loaded('mcrypt'))
        {
            $msg = sprintf($this->_aMsgsExceptions['extension_mcrypt_absente'], __CLASS__);
            throw new Exception($msg, E_USER_ERROR);
        }
        $iv = (!is_null($iv)) ? $iv : 'rand';
        /**
         * Récupération des algorithmes et modes de chiffrement disponibles.
         */
        $this->_aAlgosEncryption = mcrypt_list_algorithms();
        $this->_aModesEncryption = mcrypt_list_modes();

        /**
         * Initialisation des paramètres de configuration de l'instance.
        */
        $this->_setAlgo($algo);
        $this->_setMode($mode);
        $this->_setVecteur($iv);

        /**
         * Initialisation de l'instance elle-même.
        */
        $this->_init($grainDeSel);
    }

    /**
     * Impression par defaut d'un objet de la classe Crypt
     */
    public function __toString()
    {
        $msg  = "Algorithme de chiffrage/d&eacute;chiffrage utilis&eacute; : ".$this->_getAlgo()."<br />";
        $msg .= "Mode de chiffrement utilis&eacute; : ".$this->_getMode()."<br />";
        $msg .= "Vecteur d'initialisation cr&eacute;&eacute;/utilis&eacute; : ".$this->getVecteur();
        return $msg;
    }

    /**
     * Crée et retourne un vecteur.
     *
     * @return String Renvoie un vecteur cree pseudo-aleatoirement
     */
    public function createRandomVector()
    {
        return mcrypt_create_iv($this->getVectorSize(), MCRYPT_RAND);
    }

    /**
     * Chiffre le texte envoye en parametre et renvoie si voulu
     * le resultat encode en base 64.<br />
     *
     * Exemple d'utilisation pour chiffrer une chaine :
     * <code>$clechiffrement = "chiffrementjemframework";
     * $vecteur        = "vh8l59lvyTXblCv7TTePpDZ+vhceFQK5DyAhdo5IY7U=";
     * // Création de l'instance avec tous ses paramètres :
     * $oCrypt = new jem_chiffrement_crypt($clechiffrement, MCRYPT_RIJNDAEL_256, MCRYPT_MODE_NOFB, $vecteur   , 'fr', $vecteur);
     * // On chiffre la chaine
     * $texte          = "jemframework/www/conf";
     * $motchiffre     = $oCrypt->encrypt($texte, true);</code>
     *
     * @param   String  $txt    Texte a chiffrer
     * @param   Boolean $encode Encode ou non le texte chiffre en base 64
     * @return  String          Renvoie le texte chiffre
     */
    public function encrypt($txt, $encode = false)
    {
        $crypt = mcrypt_encrypt($this->_algo, $this->_grainDeSel, $txt, $this->_mode, $this->_vecteur);
        return (false === $encode) ? $crypt : base64_encode($crypt);
    }

    /**
     * Déchiffre le texte chiffré envoyé en paramètre.
     *
     * Si ce dernier a été chiffré en base 64 il ne faut pas
     * oublier de passer $decode à true.<br />
     *
     * Exemple d'utilisation pour déchiffrer une chaine :
     * Supposons qu'on veuille déchiffrer une chaine, au hasard par exemple la
     * chaine « 0tOjk4vRXSQPLhZBWd8G/NTNA1wc » : on doit impérativement
     * connaître le vecteur d'initialisation utilisé lors du chiffrement original
     * ainsi que l'algorithme et le mode.
     * <code>$clechiffrement = "chiffrementjemframework";
     * $vecteur        = "vh8l59lvyTXblCv7TTePpDZ+vhceFQK5DyAhdo5IY7U=";
     * // Création de l'instance avec tous ses paramètres :
     * $oCrypt = new jem_chiffrement_crypt($clechiffrement, MCRYPT_RIJNDAEL_256, MCRYPT_MODE_NOFB, $vecteur   , 'fr', $vecteur);
     * $txtc           = "0tOjk4vRXSQPLhZBWd8G/NTNA1wc";
     * $motdechiffre   = $oCrypt->decrypt($txtc, true);</code>
     *
     * @param   String  $txt    Texte a dechiffrer
     * @param   Boolean $decode Déchiffre le texte en tant que texte encode en base 64 ou non
     * @return  String          Renvoie le texte dechiffré
     */
    public function decrypt($txt, $decode = false)
    {
        if(true === $decode)
        {
            $txt = base64_decode($txt);
        }
        return mcrypt_decrypt($this->_algo, $this->_grainDeSel, $txt, $this->_mode, $this->_vecteur);
    }

    /**
     * Récupère l'algorithme utilisé.
     *
     * Renvoie le nom de l'algorythme de chiffrement/dechiffrement en cours d'utilisation
     * @return String
     */
    private function _getAlgo()
    {
        return $this->_algo;
    }

    /**
     * Permet de modifier l'algorithme a utiliser
     *
     * @param String $algo
     */
    private function _setAlgo($algo = null)
    {
        $this->_algo = (!isset($algo) || !in_array($algo, $this->_aAlgosEncryption)) ? MCRYPT_RIJNDAEL_256 : $algo;
    }

    /**
     * Récupère la taille de la clé de chiffrement.
     *
     * Récupère et retourne la taille maximale de la clef à utiliser.
     * @return int
     */
    private function getKeyMaxSize()
    {
        return mcrypt_module_get_algo_key_size($this->_algo);
    }

    /**
     * Permet de changer la clef de chiffrement/dechiffrement
     *
     * @param String $grainDeSel Clef de chiffrement/dechiffrement
     */
    private function setKey($grainDeSel)
    {
        $this->_grainDeSel = (strlen($grainDeSel) > $this->getKeyMaxSize()) ? substr($grainDeSel, 0, $this->getKeyMaxSize()) : $grainDeSel;
    }

    /**
     * @return String Renvoie le mode de chiffrement en cours d'utilisation
     */
    private function _getMode()
    {
        return $this->_mode;
    }

    /**
     * Modifie le mode de chiffrement
     *
     * @param String $mode Mode de chiffrement a utiliser
     */
    private function _setMode($mode = null)
    {
        $this->_mode = (!isset($mode) || !in_array($mode, $this->_aModesEncryption)) ? MCRYPT_MODE_NOFB : $mode;
    }

    /**
     * @return  int     Taille obligatoire a utiliser pour le vecteur d'initialisation
     */
    private function getVectorSize()
    {
        return mcrypt_get_iv_size($this->_algo, $this->_mode);
    }

    /**
     * @return  String  Renvoie la valeur du vecteur d'initilisation
     */
    public function getVecteur()
    {
        return $this->_vecteur;
    }

    /**
     * Creer un vecteur d'initialisation.
     *
     * @param   String  $vecteur    Vecteur à utiliser
     */
    private function _setVecteur($vecteur)
    {
        if($vecteur === 'rand')
        {
            $this->_vecteur = $this->createRandomVector();
        }
        else
        {
            $max = $this->getVectorSize();
            $v = substr($vecteur, 0, $max);
            if(strlen($v) != $this->getVectorSize())
            {
                $msg = sprintf($this->_aMsgsExceptions['longueur_vecteur_init'], $this->getVectorSize());
                throw new Exception($msg, E_USER_NOTICE);
            }
            else
            {
                $this->_vecteur = $v;
            }
        }
    }

    private function _init($cle)
    {
        $this->td   = mcrypt_module_open($this->_algo, '', $this->_mode, '');
        $this->ks   = mcrypt_enc_get_key_size($this->td);
        $this->_grainDeSel = substr(md5($cle), 0, $this->ks);
    }

    private function _setMessagesException()
    {
        $reploc         = realpath(dirname(__FILE__)) . DS . "locale";
        $msgs_locale    = $reploc . DS . $this->_locale .".php";
        if(!file_exists($msgs_locale))
        {
            $msgs_locale = $reploc . DS ."fr.php";
        }
        include_once($msgs_locale);
        $this->_aMsgsExceptions = $msgs_exceptions;
    }
}
