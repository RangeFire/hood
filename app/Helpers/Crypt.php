<?php

namespace App\Helpers;

class Crypt {

    private $key;

    public function __construct() {
        
        $this->key = AppHelper::$dbSyncAES_key_1;
    }

    public function encrypt($str) {
        $encrypted = openssl_encrypt($str, 'aes-256-ecb', $this->key);
        return $encrypted;
    }

    public function decrypt($str) {
        $decrypted = openssl_decrypt($str, 'aes-256-ecb', $this->key);
        return $decrypted;
    }
    
}


?>