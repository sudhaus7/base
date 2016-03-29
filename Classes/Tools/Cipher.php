<?php

namespace SUDHAUS7\Sudhaus7Base\Tools;

class Cipher {
    private $securekey, $iv;

    function __construct($textkey) {
        $this->securekey = hash('sha256', $textkey, TRUE);
        $size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_ECB);
        $this->iv = mcrypt_create_iv($size);
    }

    function encrypt($input) {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->securekey, $input, MCRYPT_MODE_ECB, $this->iv));
    }

    function decrypt($input) {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->securekey, base64_decode($input), MCRYPT_MODE_ECB, $this->iv));
    }
}
