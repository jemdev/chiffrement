<?php
namespace jemdev\chiffrement;

/**
 *
 * @author jean
 */
interface cryptInterface
{
    public function encrypt($chaineclaire, $encode = false): string;
    public function decrypt($chainechiffree, $decode = false): string;
    public function getAlgo(): string;
    public function setAlgo($algo = null);
    public function getVecteur(): string;
    public function setVecteur($vecteur);
    public function getGrainDeSel(): string;
    public function setGrainDeSel($grainDeSel = null);
}

