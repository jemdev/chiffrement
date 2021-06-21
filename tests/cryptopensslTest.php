<?php
use jemdev\chiffrement\cryptopenssl;
use PHPUnit\Framework\TestCase;
use jemdev\chiffrement\Exception;
use jemdev\chiffrement\cryptInterface;

/**
 * cryptopenssl test case.
 */
class cryptopensslTest extends TestCase
{
    protected $_salt    = '34A92C80D4AFE671';
    protected $_key     = '20C828B6ECCD0DBC389300EFAC0F4B31D820D83D2CD3B037F3D9040771E963EB';
    protected $_iv      = '31EB8CA8FE3A0EE2880145B50B36DB99';
    protected $_algo    = 'aes-256-cbc';
    protected $_txt     = "Mon texte en clair";
    protected $_chiffre;

    /**
     * @var cryptopenssl
     */
    private $_oCryptopenssl;

    protected function setUp():void
    {
        $this->_oCryptopenssl = new cryptopenssl($this->_salt, $this->_iv, $this->_algo);
    }

    /**
     * Tests cryptopenssl->__construct()
     */
    public function test__construct()
    {
        $this->assertInstanceOf(cryptInterface::class, $this->_oCryptopenssl, "L'objet retourné n'est pas une instance de cryptopenssl");
    }

    /**
     * Tests cryptopenssl->getAlgo()
     */
    public function testGetAlgo()
    {
        $algo = $this->_algo;
        $this->assertEquals($this->_algo, $this->_oCryptopenssl->getAlgo(), "L'algorithme retourné est invalide et ne correspond pas à celui déterminé dans le constructeur.");
    }

    /**
     * Tests cryptopenssl->setAlgo()
     */
    public function testSetAlgo()
    {
        $algo = 'nawak';
        $this->expectException(Exception::class);
        $this->_oCryptopenssl->setAlgo($algo);
        $algo = $this->_algo;
        $this->assertInstanceOf(cryptInterface::class, $this->_oCryptopenssl->setAlgo($algo));
    }

    /**
     * Tests cryptopenssl->encrypt()
     */
    public function testEncrypt()
    {
        $this->_chiffre = $this->_oCryptopenssl->encrypt($this->_txt);
        $this->assertIsString($this->_chiffre);
    }

    /**
     * Tests cryptopenssl->decrypt()
     */
    public function testDecrypt()
    {
        $chiffre = $this->_oCryptopenssl->encrypt($this->_txt);
        $txt = $this->_oCryptopenssl->decrypt($chiffre);
        $this->assertEquals($this->_txt, $txt, "La chaîne déchiffrée ne correspond pas au texte original.");
    }

    /**
     * Test cryptopenssl->getGrainDeSel()
     */
    public function testGetGrainDeSel()
    {
        $algo = $this->_salt;
        $this->assertEquals($this->_salt, $this->_oCryptopenssl->getGrainDeSel(), "La clé de chiffrement retournée est invalide et ne correspond pas à celle déterminée dans le constructeur.");
    }

    /**
     * Test cryptopenssl->setGrainDeSel()
     */
    public function testSetGraindDeSel()
    {
        $this->assertInstanceOf(cryptInterface::class, $this->_oCryptopenssl->setGrainDeSel(null));
        $salt = $this->_salt;
        $this->assertInstanceOf(cryptopenssl::class, $this->_oCryptopenssl->setGrainDeSel($salt));
    }
}

