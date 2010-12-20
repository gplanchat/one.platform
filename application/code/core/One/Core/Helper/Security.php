<?php

class One_Core_Helper_Security
    extends One_Core_HelperAbstract
{
    public function random($length, $hashAlgo = null, $raw = false)
    {
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= chr(mt_rand(0, 0xFF));
        }

        if ($hashAlgo !== null && in_array($hashAlgo, hash_algos())) {
            return hash($hashAlgo, $random, $raw);
        }
        if ($raw !== true) {
            return bin2hex($random);
        }
        return $random;
    }
}